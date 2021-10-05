<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Carbon\Carbon;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Support\Excel;

class ProductsAdminExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
    private $_arr;
	
	
	
	
    function build()
    {
        if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $i=0;   
        $arr =[];
        $sheet = $this->getActiveSheet();
        $sheet->setTitle('products');
        $this->_buildSheet ($sheet, $i, $arr);
        
    }

    
    private function _buildSheet ($sheet, $i=0, $arr) {
        $sheet->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b6f2a0');
        $sheet->freezePane('A2');;
        $sheet->getStyle('A1:Z1')->getAlignment()->setVertical('center');
       
       $header=[
       '1'=>['name'=>'Код 1С','width'=>'12'],
       '2'=>['name'=>'Артикул','width'=>'0'],
       '3'=>['name'=>'Арт.старый','width'=>'0'],
       '4'=>['name'=>'Наименование','width'=>'20'],
       '5'=>['name'=>'Модельный ряд','width'=>'15'],
       '6'=>['name'=>'Тип','width'=>'0'],
       '7'=>['name'=>'Тип 1С','width'=>'0'],
       '8'=>['name'=>'Вкл?','width'=>'7'],
       '9'=>['name'=>'Отобр?','width'=>'7'],
       '10'=>['name'=>'Для прод?','width'=>'7'],
       '11'=>['name'=>'Для заказа?','width'=>'7'],
       '12'=>['name'=>'Для витрины?','width'=>'7'],
       '13'=>['name'=>'Гарантия?','width'=>'7'],
       '14'=>['name'=>'Срок гар','width'=>'7'],
       '15'=>['name'=>'Доп.гар','width'=>'7'],
       '16'=>['name'=>'Срок дг','width'=>'7'],
       '17'=>['name'=>'Услуга?','width'=>'7'],
       '18'=>['name'=>'Детали','width'=>'15'],
       '19'=>['name'=>'Документация','width'=>'15'],
       '20'=>['name'=>'Взр схемы','width'=>'15'],
       '21'=>['name'=>'Рук.польз','width'=>'15'],
       '22'=>['name'=>'Кат.зап.','width'=>'15'],
       '23'=>['name'=>'Серт.','width'=>'15'],
       '24'=>['name'=>'РРЦ','width'=>'15'],
       '25'=>['name'=>'РРЦ Руб','width'=>'15'],
       
        
        ];
        
        foreach ($header as $cell => $value) {
            $sheet->setCellValue(trans('site::excel.col.'.$cell).'1', $value['name'])->getStyle(trans('site::excel.col.'.$cell).'1')
            ->getFont(trans('site::excel.col.'.$cell).'1')->setBold(true);
            $sheet->getStyle(trans('site::excel.col.'.$cell).'1')
            ->getAlignment()->setWrapText(true);
            
            if($value['width'] > 0){
                $sheet->getColumnDimension(trans('site::excel.col.'.$cell))->setWidth($value['width']);
            } else {
                $sheet->getColumnDimension(trans('site::excel.col.'.$cell))->setAutoSize(true);            
            }

            
        }
        $row_num = 2;
        $column = 1;
        $second = 0;
        
        
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');
        $sheet->getStyle(trans('site::excel.col.8') . ':' .trans('site::excel.col.25'))->getAlignment()->setHorizontal('center');
        $sheet->getStyle(trans('site::excel.col.1') . ':' .trans('site::excel.col.7'))->getAlignment()->setHorizontal('left');
        
        
        foreach ($this->repository->all() as $key => $product) {
                     
        $column=1;
        
        $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->id];
           
        
        $column++; //2
        $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->sku];
                
           
        $column++; //3
        $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->old_sku];
           
        $column++; //4
        $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->name];
                
        $column++; //5
        if($product->equipment_id) {
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->equipment->name];
           
        } 
           
                
        $column++; 
        
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->type ? $product->type->name : 'не указан'];
           
                
        $column++; 
        
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->group ? $product->group->type->name : 'не указан'];
           
               
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->enabled ? 'да' : 'нет'];
        
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->show_ferroli ? 'да' : 'нет'];
                
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->for_sale ? 'да' : 'нет'];
                    
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->for_preorder ? 'да' : 'нет'];
                
        	         
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->for_stand ? 'да' : 'нет'];
                
        	         
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->warranty ? 'да' : 'нет'];
                
        	         
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->warranty_time_month ? $product->warranty_time_month : 'нет'];
                
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->warranty_extended ? 'да' : 'нет'];
                
        	         
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->warranty_extended_time_month ? $product->warranty_time_month : 'нет'];
                
        	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->service ? 'да' : 'нет'];
         	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->availableDetails()->count() ? $product->availableDetails()->count() : 'нет'];
           	 
        $column++; 
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->datasheets->count() ? $product->datasheets->count() : 'нет'];
                
        $column++; 
            
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->datasheets()->whereHas('schemes')->count() ? $product->datasheets()->whereHas('schemes')->count() : 'нет'];
                     
        $column++; 
            
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>
                $product->datasheets()->whereHas('file' ,function ($q) {$q->where('type_id','6');})->count() ? 
                    $product->datasheets()->whereHas('file' , function ($q) {$q->where('type_id','6');})->count() : 'нет'];
                
        $column++; 
            
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>
                    $product->datasheets()->whereHas('file' , function ($q) {$q->where('type_id','4');})->count() ? 
                        $product->datasheets()->whereHas('file' , function ($q) {$q->where('type_id','4');})->count() : 'нет'];
                   
        $column++; 
            
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>
                    $product->datasheets()->whereHas('file' , function ($q) {$q->where('type_id','8');})->count() ? 
                        $product->datasheets()->whereHas('file' , function ($q) {$q->where('type_id','8');})->count() : 'нет'];
                     
                 
        $column++; 
            
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->retail_price ? $product->retail_price->price : 'нет'];
                    
                 
        $column++; 
            
            $arr[$row_num][$column]=['cell'=>trans('site::excel.col.'.$column) . $row_num , 'val'=>$product->retail_price_rub ? $product->retail_price_rub->price : 'нет'];
                    
         
         
         $row_num++;
         }
         
      
        foreach($arr as $row) {
            foreach($row as $cell){
                $sheet->setCellValue($cell['cell'], $cell['val']);
            }
        
        }
      
      
        
    }
    
    
    
    
    
}
