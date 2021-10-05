<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Validator;
use ServiceBoiler\Prf\Site\Events\Digift\BonusCreateEvent;
use ServiceBoiler\Prf\Site\Events\MountingStatusChangeEvent;
use ServiceBoiler\Prf\Site\Http\Requests\Api\DigiftServiceRequest;
use ServiceBoiler\Prf\Site\Models\DigiftUser;
use ServiceBoiler\Prf\Site\Models\RetailSaleReport;
use ServiceBoiler\Prf\Site\Models\User;

class RetailSaleReportRequest extends FormRequest
{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

		switch ($this->method()) {

			case 'PUT':
			case 'PATCH':
				{
					return [
						'retail_sale_report.status_id' => 'required|exists:mounting_statuses,id',
					];
				}
			default:
				return [];
		}
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'retail_sale_report.status_id' => trans('site::retail_sale_report.status_id'),

		];
	}

	/**
	 * @param Mounting $retail_sale_report
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException
	 */
	public function update(RetailSaleReport $retail_sale_report)
	{  
        $retail_sale_report->fill($this->input('retail_sale_report'));
		$status_changed = $retail_sale_report->isDirty('status_id');

		if ($retail_sale_report->save()) {
			if ($retail_sale_report->getAttribute('status_id') == 2) {
				/** @var User $user */
				$user = $retail_sale_report->user;
				/*$user->makeDigiftUser();
				$retail_sale_report->digiftBonus()->create([
					'user_id' => $user->digiftUser->id,
					'operationValue' => $retail_sale_report->total
				]);*/
				//event(new BonusCreateEvent($retail_sale_report));
			}
			if ($status_changed) {
				//event(new MountingStatusChangeEvent($retail_sale_report));
			}
		}
	}
}
