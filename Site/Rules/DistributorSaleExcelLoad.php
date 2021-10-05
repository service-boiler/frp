<?php

namespace ServiceBoiler\Prf\Site\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Imports\Excel\DistributorSaleExcelLoadFilter;

class DistributorSaleExcelLoad implements Rule
{

    private $_errors;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  UploadedFile $file
     * @return bool
     */
    public function passes($attribute, $file)
    {

        $inputFileType = ucfirst($file->getClientOriginalExtension());
        $filterSubset = new DistributorSaleExcelLoadFilter();
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($filterSubset);
        try {
            $spreadsheet = $reader->load($file->getPathname());

            $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();


            $data = [];
            $errors = [];
            $sku = collect([]);

            foreach ($rowIterator as $r => $row) {

                $cellIterator = $row->getCellIterator();

                foreach ($cellIterator as $c => $cell) {

                    $value = $cell->getValue();
                    $data[$r][$c] = $cell->getValue();

                    switch ($c) {
                        case 'A':
                            if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.artikul_is_null');
                            } else {
                                $value = (string)trim($value);
                                if (!Product::query()->where('sku', $value)->exists()) {
                                    $errors[$r][$c] = trans('site::storehouse_product.error.load.artikul_not_found');
                                } else {
                                    $data[$r][$c] = $value;
                                }
                            }
                            break;
                        case 'B':

                            if (is_null($value)) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.quantity_is_null');
                            } elseif (is_string($value)) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.quantity_not_number');
                            } elseif ($value - floor($value) !== 0.0) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.quantity_not_integer');
                            } elseif ($value <= 0) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.quantity_not_positive');
                            } elseif ($value >= config('storehouse_product_max_quantity', 10000)) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.quantity_max');
                            } else {
                                $data[$r][$c] = (int)$value;
                            }
                            break;
                            
                     /*   case 'C':
                        
                             if (is_null($value)) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.date_error');
                            } elseif (is_string($value) && !strtotime($value)) {
                                $errors[$r][$c] = trans('site::storehouse_product.error.load.date_error');
                            } else {
                                    $data[$r][$c] = $value;
                                    }
                            
                            
                            break; */
                    }
                }
            }

            if (!empty($errors)) {

                $this->_errors = view('site::distributor_sales.excel_error', compact('data', 'errors'))->render();

                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->_errors = trans('site::storehouse_product.error.load.file', ['error' => $e->getMessage()]);
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->_errors;
    }
}