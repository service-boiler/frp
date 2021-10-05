<?php

namespace ServiceBoiler\Prf\Site\Imports\GoogleSheet;


use Carbon\Carbon;
use PulkitJalan\Google\Facades\Google;
use ServiceBoiler\Prf\Site\Exceptions\Certificate\CertificateException;
use ServiceBoiler\Prf\Site\Models\Certificate;
use Revolution\Google\Sheets\Facades\Sheets;

class CertificateExcel
{

	/**
	 * @param string $email
	 *
	 * @throws CertificateException
	 */
	public function parse(string $email)
	{


		if(($spreadsheet_id = env('SPREADSHEET_ID', false)) === false) {
			throw new CertificateException(trans('site::certificate.error.spreadsheet_id_not_found'));
		}
		if(($spreadsheet_range = env('SPREADSHEET_RANGE', false)) === false) {
			throw new CertificateException(trans('site::certificate.error.spreadsheet_range_not_found'));
		}

		Sheets::setService(Google::make('sheets'));
		Sheets::spreadsheet($spreadsheet_id);
		$collection = Sheets::sheet($spreadsheet_range)->get();
        
        $filtered = $this->filterCollection($collection, $email);
        if ($filtered->isEmpty()) {
			throw new CertificateException(trans('site::certificate.error.not_found'));
		}

		$certificate = $filtered->first();
        $data = [
			'id' => config('site.certificate_first_letter', 'R').Carbon::createFromFormat('d.m.Y H:i:s', $certificate[0])->format('ymd').auth()->user()->getKey(),
			'type_id' => 2,
			'name' => $certificate[2],
			'organization' => auth()->user()->name,
			'created_at' => Carbon::createFromFormat('d.m.Y H:i:s', $certificate[0])->format('Y-m-d H:i:s')
		];
		$engineer=auth()->user()->engineers()->first();
        foreach ($engineer->certificates()->get() as $certificate){
            $certificate->engineer()->dissociate()->save();
        }
        
            if (
                !is_null($data['id'])
                && ($oldcerts = Certificate::query()
                    ->where('type_id', '2')
                    ->where('id', $data['id']))->exists()
            ) {
                $oldcerts->first()->engineer()->associate($engineer)->save();
            }
            else {
            auth()->user()->engineers()->first()->certificates()->create($data);
            }

	}

	private function filterCollection($collection, $email)
	{
		return $collection->reject(function ($value) use ($email) {
			return $value[4] != $email || !in_array(str_replace(' / 10', '', $value[1]), config('site.certificate_scores'));
		});
	}

}