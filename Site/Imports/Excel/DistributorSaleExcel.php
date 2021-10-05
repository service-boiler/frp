<?php

namespace ServiceBoiler\Prf\Site\Imports\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use ServiceBoiler\Prf\Site\Models\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DistributorSaleExcel
{
    private $_data = [];

	/**
	 * @param UploadedFile $path
	 *
	 * @return array
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
    public function get(UploadedFile $path)
    {
        
        $inputFileType = ucfirst($path->getClientOriginalExtension());
        $filterSubset = new DistributorSaleExcelLoadFilter();
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($filterSubset);

        $spreadsheet = $reader->load($path->getPathname());

        $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();

        foreach ($rowIterator as $r => $row) {

            $cellIterator = $row->getCellIterator();

            foreach ($cellIterator as $c => $cell) {

                switch ($c) {
                    case 'A':
                        $sku = (string)trim($cell->getValue());
                        
                        /** @var Product $product */
                        $product = Product::query()->where('sku', $sku)->firstOrFail();
                        
                        break;
                    case 'B':

                        $quantity = (int)$cell->getValue();
                         break;
                         
                    
                }
            }
            array_push($this->_data, [
                'product_id' => $product->getKey(),
                'quantity'   => $quantity
                
            ]);
        }

        return $this->_data;
    }
}
