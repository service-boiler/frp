<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Carbon\Carbon;
use ServiceBoiler\Prf\Site\Models\RetailOrder;
use ServiceBoiler\Prf\Site\Support\Excel;

class RetailOrderExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
	
	
	
	
    function build()
    {
        if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $i=0;    
        $sheet = $this->getActiveSheet();
        $sheet->setTitle('retail_orders');
        $this->_buildSheet ($sheet, $i);
        
    }

    
    private function _buildSheet ($sheet, $i=0) {
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b6f2a0');
        $sheet->freezePane('A2');;
        $sheet->getStyle('A1:H1')->getAlignment()->setVertical('center');
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');
       
       $header=['1'=>'№ заявки',
           '2'=>'Дата создания',
        '3'=>'Дилер',
        '4'=>'Клиент',
        '5'=>'Телефон',
        '6'=>'Комментарий',
        '7'=>'Товары',
        '8'=>'шт',
        '9'=>'Цена',
        '10'=>'Статус',
        '11'=>'Регион',

        ];
        
        foreach ($header as $cell => $value) {
            $sheet->setCellValue(trans('site::excel.col.'.$cell).'1', $value)->getStyle(trans('site::excel.col.'.$cell).'1')
            ->getFont(trans('site::excel.col.'.$cell).'1')->setBold(true);
            $sheet->getStyle(trans('site::excel.col.'.$cell).'1')
            ->getAlignment()->setWrapText(true);
            
        }
        $row_num = 2;
        //Ширина столбцов
        $cols_width=[
            '1'=>'12','2'=>'12','3'=>'35','4'=>'35','5'=>'15','6'=>'40','7'=>'35',
            '8'=>'5','9'=>'10','10'=>'10','11'=>'15',
            ];
        
        foreach($cols_width as $key=>$width) {
            if($width > 0){
                $sheet->getColumnDimension(trans('site::excel.col.'.$key))->setWidth($width);
            } else {
                $sheet->getColumnDimension(trans('site::excel.col.'.$key))->setAutoSize(true);            
            }

        
        }

        foreach ($this->repository->all() as $key => $order) {
                     $this->_buildretailOrder($sheet, $order, $row_num);
                     $row_num++;
            if ($order->items->count() > 1) {
                $row_num = $row_num + $order->items->count() - 1;
            }
                    
         }

    
    }
    
    
    
    private function _buildretailOrder($sheet, retailOrder $order, $row_num)
    {

        $column = 1;

        $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $order->id);
        $sheet->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');

        $column++;

        //2
        $sheet
            ->setCellValue(trans('site::excel.col.' . $column) . $row_num, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->getAttribute('created_at')))
            ->getStyle(trans('site::excel.col.' . $column) . $row_num)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
        $sheet->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('center');


        $column++; //3
        $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $order->userAddress->user->name)
            ->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');

        $column++;//4
        $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $order->contact)
            ->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');

        $column++;//4
        $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $order->phone_number)
            ->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');


        $column++;//4
        $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $order->comment)
            ->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');

        $column++;//8
        foreach ($order->items as $ki => $item) {
            if ($ki > 0) {
                $row_num++;

            }
            $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $item->product->name)
                ->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');
            $sheet->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setWrapText(true);

            $sheet->setCellValue(trans('site::excel.col.' . ($column + 1)) . $row_num, $item->quantity)
                ->getStyle(trans('site::excel.col.' . ($column + 1)) . $row_num)->getAlignment()->setHorizontal('left');

            $sheet->setCellValue(trans('site::excel.col.' . ($column + 2)) . $row_num, $item->price)
                ->getStyle(trans('site::excel.col.' . ($column + 2)) . $row_num)->getAlignment()->setHorizontal('left');
            $sheet->getStyle(trans('site::excel.col.' . ($column + 2)) . $row_num)->getNumberFormat()->setFormatCode('# ##0\ \р');


            if ($order->items->count() > 1) {
                $sheet->setCellValue(trans('site::excel.col.1') . $row_num, $order->id);
                $sheet->getStyle(trans('site::excel.col.1') . $row_num)->getAlignment()->setHorizontal('left');
                $sheet
                    ->setCellValue(trans('site::excel.col.2') . $row_num, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->getAttribute('created_at')))
                    ->getStyle(trans('site::excel.col.2') . $row_num)
                    ->getNumberFormat()
                    ->setFormatCode('dd.mm.yyyy');
                $sheet->getStyle(trans('site::excel.col.2') . $row_num)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue(trans('site::excel.col.3') . $row_num, $order->userAddress->user->name)
                    ->getStyle(trans('site::excel.col.3') . $row_num)->getAlignment()->setHorizontal('left');

                $sheet->getStyle(trans('site::excel.col.10') . $row_num)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB(substr($order->status->color, 1, 6));

            }
        }
            $column++;
            $column++;
            $column++;//11

            $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $order->status->name)
                ->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');
            $sheet->getStyle(trans('site::excel.col.' . $column) . $row_num)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB(substr($order->status->color, 1, 6));
            $sheet->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setWrapText(true);

            $column++;//4
            $sheet->setCellValue(trans('site::excel.col.' . $column) . $row_num, $order->userAddress->region->name)
                ->getStyle(trans('site::excel.col.' . $column) . $row_num)->getAlignment()->setHorizontal('left');





    }
    
    

}
