<?php

namespace ServiceBoiler\Prf\Site\Rules;

use ErrorException;
use Illuminate\Contracts\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\LoadEmptyDataException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\UrlNotExistsException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\XmlLoadFailedException;
use ServiceBoiler\Prf\Site\Imports\Url\DistributorSaleXml;

class DistributorSaleUrlLoad implements Rule
{

	private $_errors = [];

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string $attribute
	 * @param  string $url
	 *
	 * @return bool
	 * @throws \Throwable
	 */
	public function passes($attribute, $url)
	{


		try {

			$storehouseXml = new DistributorSaleXml($url);
			$storehouseXml->import();


			if ($storehouseXml->errors()->isNotEmpty()) {
				$exceptions = $storehouseXml->errors()->toArray();
				$data = $storehouseXml->data()->toArray();
				$products = $storehouseXml->values()->toArray();
				$this->_errors = view('site::distributor_sales_product.url', compact('data', 'exceptions', 'products'))->render();

				return false;
			}

			return true;

		} catch (\Exception $exception) {
			$this->_errors[] = $exception->getMessage();

			return false;
		}

	}

	/**
	 * Get the validation error message.
	 *
	 * @return array
	 */
	public function message()
	{   
        return $this->_errors;
	}
}