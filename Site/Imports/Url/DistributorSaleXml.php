<?php

namespace ServiceBoiler\Prf\Site\Imports\Url;

use Carbon\Carbon;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Contracts\Importable;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\LoadEmptyDataException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\ProductException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\UrlNotExistsException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\XmlLoadFailedException;
use ServiceBoiler\Prf\Site\Support\Import\UrlImport;

class DistributorSaleXml extends UrlImport
{

	/**
	 * @return \ServiceBoiler\Prf\Site\Contracts\Importable
	 */
	function import(): Importable
	{
		try {
			$url = $this->url();

			if (!is_null($url)) {

				if (!url_exists($url)) {
					throw new UrlNotExistsException(trans('site::distributor_sales.error.upload.url_not_exist', compact('url')));
				}

				try {
					$upload = simplexml_load_file($url);
				} catch (\ErrorException $e) {
					throw new XmlLoadFailedException(trans('site::distributor_sales.error.upload.xml_load_failed', compact('url')));
				}

				if ($upload === false) {
					throw new XmlLoadFailedException(trans('site::distributor_sales.error.upload.xml_load_failed', compact('url')));
				}


				if (empty($upload->shop->offers->offer)) {
					throw new LoadEmptyDataException(trans('site::distributor_sales.error.upload.data_is_empty'));
				}

				foreach ($upload->shop->offers->offer as $offer) {

					if (isset($offer['id'])) {

						$offer_id = (string)$offer['id'];

						$product = null;

						try {
							
                            if ((bool)$offer->vendorCode === false) {

								// Артикул не найден
								throw new ProductException(trans('site::distributor_sales.error.upload.sku_not_exist', compact('offer_id')));
							}

							if (($model = Product::query()->where('sku', ($sku = trim((string)$offer->vendorCode))))->doesntExist()) {

								// Товар не найден
								throw new ProductException(trans('site::distributor_sales.error.upload.sku_not_found', compact('sku')));
							}

							$product = $model->first();

							
							if ((bool)$offer->quantity === false) {

								// Количество не найдено
								throw new ProductException(trans('site::distributor_sales.error.upload.quantity_not_exist', compact('sku')));
							}

							if (ctype_digit((string)$offer->quantity) === false) {

								// Количество не является целым числом
								throw new ProductException(trans('site::distributor_sales.error.upload.quantity_not_integer', compact('sku')));
							}

							if (($quantity = (int)$offer->quantity) <= 0) {

								// Количество меньше нуля
								throw new ProductException(trans('site::distributor_sales.error.upload.quantity_not_positive', compact('sku')));
							}
                            
							if (($date_sale = (string)$offer->date_sale) === false) {

								throw new ProductException(trans('site::distributor_sales.error.upload.date_sale_not_exist', compact('sku')));
							}
							if(!preg_match('/\d\d\d\d-\d\d-\d\d/', $date_sale)) {
                                throw new ProductException(trans('site::distributor_sales.error.upload.date_sale_not_correct', compact('sku')));
                            }
                             
                             if (($date_sale = Carbon::createFromFormat('Y-m-d',$date_sale)) === false) {
                            

								throw new ProductException(trans('site::distributor_sales.error.upload.date_sale_not_correct', compact('sku')));
							 }
                         
							if ($quantity > ($max = config('site.storehouse_product_max_quantity', 20000))) {

								// Количество превышает максимум
								throw new ProductException(trans('site::distributor_sales.error.upload.quantity_max', compact('max', 'sku')));
							}

							$this->values->put($offer_id, [
								'product_id' => $product->getKey(),
								'quantity' => $quantity,
                                'date_sale' => $date_sale,
							]);

						} catch (ProductException $exception) {
							$this->errors->put($offer_id, $exception->getMessage());
						} finally {
							$this->data->put($offer_id, [
								'sku' => (string)$offer->vendorCode,
								'product_id' => isset($product) ? $product->getKey() : $offer_id,
								'name' => isset($product) ? $product->getAttribute('name') : $offer_id,
								'quantity' => (string)$offer->quantity,
								'date_sale' => (string)$offer->date_sale,
							]);
						}
					}
				}
			}
		} catch (\Exception $exception) {
			$this->errors->push($exception->getMessage());
		}

		return $this;

	}
}