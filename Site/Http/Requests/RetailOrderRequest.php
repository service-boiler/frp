<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use  Cart;
use ServiceBoiler\Prf\Site\Events\OrderCreateEvent;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Product;

use ServiceBoiler\Prf\Site\Http\Requests\Admin\MailingSendRequest;
use ServiceBoiler\Prf\Site\Mail\RetailOrder\RetailOrderCreateEmail;

class RetailOrderRequest extends FormRequest
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

    /*

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		switch ($this->method()) {
			case 'GET':
				{
					return [];
				}
			case 'DELETE':
				{
					return [];
				}
			case 'POST':
				{
					return [
						'comment' => 'max:255',
						'order.contact' => 'required|max:255',
						'products' => [
							'required',
							'array',
						],
						'recipient' => [
							'required',
							'array',
						],
					];
				}
			case 'PUT':
			case 'PATCH':
				{
					return [];
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
			'products' => trans('site::messages.products'),
			'order.comments' => trans('site::messages.comments'),
			'order.address_id' => trans('site::order.address_id'),
			'order.contragent_id' => trans('site::order.contragent_id'),
			'order.contacts_comment' => trans('site::order.contacts_comment'),
			'recipient' => "Дилер",
		];
	}

	public function createRetailOrder()
	{
		
		
		
		$data = [];
		$retailorders = collect([]);
		$cart_products = Cart::toArray($this->input('products', []));
		$data[0] = $cart_products;
		OrderRetail::create();
		//dd($data);
		
		foreach($this->input('recipient', []) as $address_id)
		{
		
		$address = Address::query()->find($address_id);
		
		            Mail::to($address->email)
                ->send(new RetailOrderCreateEmail(
                    "subj",
                    "input('content')"
                ));
				
				
				
		//event(new OrderCreateEvent($order));
		//use ServiceBoiler\Prf\Site\Events\OrderCreateEvent;
		
        }
		
		//dd($address->email);
		
		
		
		
		
		


	}
}
