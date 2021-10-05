<?php

namespace ServiceBoiler\Prf\Site\Rules;

use ErrorException;
use Illuminate\Contracts\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Exceptions\Storehouse\LoadEmptyDataException;
use ServiceBoiler\Prf\Site\Exceptions\Storehouse\UrlNotExistsException;
use ServiceBoiler\Prf\Site\Exceptions\Storehouse\XmlLoadFailedException;
use ServiceBoiler\Prf\Site\Imports\Url\StorehouseXml;

class StorehouseUrlLoad implements Rule
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

			$storehouseXml = new StorehouseXml($url);
			$storehouseXml->import();


			if ($storehouseXml->errors()->isNotEmpty()) {
				$exceptions = $storehouseXml->errors()->toArray();
				$data = $storehouseXml->data()->toArray();
				$products = $storehouseXml->values()->toArray();
				//dump($exceptions);
				//dump($data);
				//dd($products);
				$this->_errors = view('site::storehouse_product.url', compact('data', 'exceptions', 'products'))->render();

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