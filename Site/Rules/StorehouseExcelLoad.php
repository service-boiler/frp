<?php

namespace ServiceBoiler\Prf\Site\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Imports\Url\StorehouseExcelLoadFilter;

use ServiceBoiler\Prf\Site\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File as HttpFile;


class StorehouseExcelLoad implements Rule
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
       
       $rf = File::query()->make([
		    'path'    => Storage::disk('storehouses')->putFile('', new HttpFile($file->getPathName())),
		    'mime'    => $file->getMimeType(),
		    'storage' => 'storehouses',
		    'type_id' => '4',
		    'size'    => $file->getSize(),
		    'name'    => $file->getClientOriginalName(),
	    ]);
        
        
        $inputFileType = ucfirst($file->getClientOriginalExtension());
        $filterSubset = new StorehouseExcelLoadFilter();
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
                                } elseif ($sku->contains($value)) {
                                    $errors[$r][$c] = trans('site::storehouse_product.error.load.duplicate');
                                } else {
                                    $sku->push($value);
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
                         
                    }
                    
                }
                
               if(empty($data[$r]['B'])) {
                $errors[$r]['B'] = 'Ошибка выборки данных. Проверьте таблицу. Объединенных и пустых ячеек не должно быть.';
                $errors[$r]['A'] = 'Ошибка выборки данных. Проверьте таблицу. Объединенных и пустых ячеек не должно быть.';
               
               }
                    
                
            }

            if (!empty($errors)) {
                
                $this->_errors = view('site::storehouse_product.excel', compact('data', 'errors'))->render();

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