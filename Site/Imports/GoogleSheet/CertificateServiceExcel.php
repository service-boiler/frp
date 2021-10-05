<?php

namespace ServiceBoiler\Prf\Site\Imports\GoogleSheet;


use Carbon\Carbon;
use PulkitJalan\Google\Facades\Google;
use ServiceBoiler\Prf\Site\Exceptions\Certificate\CertificateException;
use ServiceBoiler\Prf\Site\Models\Certificate;
use Revolution\Google\Sheets\Facades\Sheets;

class CertificateServiceExcel
{

	/**
	 * @param string $engineer_id
	 *
	 * @throws CertificateException
	 */
	public function parse(string $engineer_id)
	{
        $certificate_type_id = '1';

		if(($spreadsheet_id = env('SPREADSHEET_SRV_ID', false)) === false) {
			throw new CertificateException(trans('site::certificate.error.spreadsheet_id_not_found'));
		}
		if(($spreadsheet_range = env('SPREADSHEET_SRV_RANGE', false)) === false) {
			throw new CertificateException(trans('site::certificate.error.spreadsheet_range_not_found'));
		}

		Sheets::setService(Google::make('sheets'));
		Sheets::spreadsheet($spreadsheet_id);
		$collection = Sheets::sheet($spreadsheet_range)->get();
        $filtered = $this->filterCollection($collection, $engineer_id);
        
		if ($filtered->isEmpty()) {
			throw new CertificateException(trans('site::certificate.error.not_found'));
		}

		$certificate = $filtered->first();
		$data = [
			'id' => config('site.certificate_srv_first_letter', 'S').Carbon::createFromFormat('d.m.Y H:i:s', $certificate[0])->format('ymd').'0'.auth()->user()->getKey().'0'.$engineer_id,
			'type_id' => $certificate_type_id,
			'name' => $certificate[2],
			'organization' => auth()->user()->name,
			'created_at' => Carbon::createFromFormat('d.m.Y H:i:s', $certificate[0])->format('Y-m-d H:i:s')
		];
        $engineer=auth()->user()->engineers()->where('id', $engineer_id)->first();
       $oldcerts = Certificate::query()
                    ->where('type_id', $certificate_type_id)
                    ->where('id', $data['id'])->get();
        
        if (($oldcerts = Certificate::query()
                    ->where('type_id', $certificate_type_id)
                    ->where('id', $data['id']))->exists()) {
                   
			$oldcerts->first()->engineer()->associate($engineer)->save();
		}
        else {
        $engineer->certificates()->create($data);
        }
        
	}

	private function filterCollection($collection, $engineer_id)
	{
		return $collection->reject(function ($value) use ($engineer_id) {
			return $value[3] != $engineer_id || !in_array(str_replace(' / 10', '', $value[1]), config('site.certificate_scores'));
		});
	}

}