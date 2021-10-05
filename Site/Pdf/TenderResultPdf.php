<?php

namespace ServiceBoiler\Prf\Site\Pdf;


use ServiceBoiler\Prf\Site\Support\Pdf;

class TenderResultPdf extends Pdf
{

    function build()
    {
        $tender=$this->model;
        
        $font_size = $this->defaults['font_size'];
        $font_size_small = $this->defaults['font_size_small'];
        $line_height = $this->defaults['line_height'];
        $this->AddPage();
        $this->SetLeftMargin(20);
        $this->SetRightMargin(20);
        $this->SetFont('Verdana', '', $font_size);
        $this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/letter-1.jpg',5,10,200);
        $this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/logo.jpg',15,10,30);
        $this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/evl-sign.jpg',140,46,30);
        
        $this->SetFont('Verdana', '', '7');
        $this->Cell(0, $line_height, w1251('Приложение №1 к Регламенту согласования объектных скидок ООО «ФерролиРус» и ИЗАО "ФерролиБел"'), 0, 1, 'R');
        $this->Cell(0, $line_height, w1251('Приложение №2'), 0, 1, 'R');
        $this->Cell(0, $line_height, w1251('К Протоколу согласования условий сотрудничества'), 0, 1, 'R');
        $this->Cell(0, $line_height, w1251('Между '.$tender->distributor->name .' и ООО ФерролиРус'), 0, 1, 'R');
        $this->Cell(0, $line_height, w1251('Заявка №' .$tender->id .' от ' .$tender->created_at->format('d.m.Y')), 0, 1, 'R');

        $this->Cell(0, $line_height, w1251('Дата внесения в базу: ' .$tender->created_at->format('d.m.Y')), 0, 1, 'R');
        $this->Cell(0, $line_height, w1251('Дата согласования заявки: ' .$tender->director_approved_date->format('d.m.Y')), 0, 1, 'R');
        
        $this->ln(5);
        
        $this->Cell(0, $line_height, w1251('Утверждаю:_____________________________Евлентьев Л.С.'), 0, 1, 'R');
        $this->ln(5);
        
        $this->SetFont('Verdana', 'B', $font_size);
        $this->Cell(0, $line_height, w1251('Форма подтверждения скидок на обекты'), 0, 1, 'C');
        $this->Line(20, $this->GetY(), 190, $this->GetY());
        $this->SetFont('Verdana', '', $font_size);
        $this->ln(5);
        $this->Cell(0, $line_height, w1251('Ответственный менеджер: ' .$tender->user->name), 0, 1, 'L');
        $this->Cell(0, $line_height, w1251('Руководитель отдела: ' . ($tender->user->chief ? $tender->user->chief->name : '') .' (дата согласования: '.$tender->head_approved_date->format('d.m.Y') .')'), 0, 1, 'L');
        
        
        $this->ln(5);
        $this->SetFont('Verdana', 'B', $font_size);
        $this->Cell(0, $line_height, w1251('Объект тендера, информация о закупке.'), 0, 1, 'L');
        $this->SetFont('Verdana', '', $font_size);
        
        $this->Cell(50, $line_height, w1251('Планируемая дата закупки:'), 0, 0, 'L');
        $this->Cell(0, $line_height, w1251(trans('site::messages.months_cl.' .$tender->planned_purchase_month) .' '  .$tender->planned_purchase_year), 0, 1, 'L');
        
        $this->Cell(50, $line_height, w1251('Цена действительна до:'), 0, 0, 'L');
        $this->Cell(0, $line_height, w1251($tender->date_price->format('d.m.Y')), 0, 1, 'L');
        
        $this->Cell(50, $line_height, w1251('Курс для дистрибьютора:'), 0, 0, 'L');
        $this->Cell(0, $line_height, w1251(round($tender->rates,2) .'(коридор курса от ' .round($tender->rates_min,2) .' до ' .round($tender->rates_max,2) .')'), 0, 1, 'L');
        
        $this->Cell(50, $line_height, w1251('Курс для объекта:'), 0, 0, 'L');
        $this->Cell(0, $line_height, w1251(round($tender->rates_object,2) .'(коридор курса от ' .round($tender->rates_object_min,2) .' до ' .round($tender->rates_object_max,2) .')'), 0, 1, 'L');
        
        foreach($tender->tenderProducts as $item) {
            $this->Cell(50, $line_height, w1251('Продукт модель:'), 0, 0, 'L');
            $this->SetFont('Verdana', 'B', $font_size);
            $this->Cell(0, $line_height, w1251($item->product->name), 0, 1, 'L');
            $this->SetFont('Verdana', '', $font_size);
            
            $this->Cell(50, $line_height, w1251('Количество:'), 0, 0, 'L');
            $this->SetFont('Verdana', 'B', $font_size);
            $this->Cell(0, $line_height, w1251($item->count) .w1251(" шт"), 0, 1, 'L');
            $this->SetFont('Verdana', '', $font_size);
            
            $this->Cell(50, $line_height, w1251('РРЦ:'), 0, 0, 'L');
            $this->Cell(0, $line_height, w1251('€ ' .$item->product->retailPriceEur->valueRaw), 0, 1, 'L');
            
            $this->Cell(50, $line_height, w1251('Скидка для дистрибьютора:'), 0, 0, 'L');
            $this->Cell(0, $line_height, w1251($item->discount .'%'), 0, 1, 'L');
            
            $this->Cell(50, $line_height, w1251('Скидка для объекта:'), 0, 0, 'L');
            $this->Cell(0, $line_height, w1251($item->discount_object .'%'), 0, 1, 'L');
            
            $this->Cell(50, $line_height, w1251('Цена для дистрибьютора:'), 0, 0, 'L');
            $this->Cell(0, $line_height, w1251('€ ' .round($item->price_distr_euro,0) .' (' .round($item->price_distr_euro * $tender->rates,0) .' руб.)'), 0, 1, 'L');
            
            $this->Cell(50, $line_height, w1251('Цена для объекта:'), 0, 0, 'L');
            $this->Cell(0, $line_height, w1251('€ ' .round($item->price_object_euro,0) .' (' .round($item->price_object_euro * $tender->rates_object,0) .' руб.)'), 0, 1, 'L');
            
            $this->Cell(50, $line_height, w1251('Доходность дистрибьютора:'), 0, 0, 'L');
            $this->Cell(0, $line_height, w1251('€ ' .round($item->profit_euro,0) .' (' .round($item->profit_euro*100 / $item->price_distr_euro,1) .'%, ' .($tender->rates*$item->profit_euro) .'руб.)'), 0, 1, 'L');
         
        }
        
           
            $this->Cell(50, $line_height, w1251('Конкурентные предложения:'), 0, 0, 'L');
            $this->Cell(0, $line_height, $this->write(6 , w1251($tender->rivals)), 0, 1, 'L');
            
            /*
            $this->Cell(50, $line_height, w1251('Результат тендера:'), 0, 0, 'L');
            $this->Cell(0, $line_height, $this->write(6 , w1251($tender->result)), 0, 1, 'L');
        
            
            $this->Cell(50, $line_height, w1251('Примечания:'), 0, 0, 'L');
            $this->Cell(0, $line_height, $this->write(6 , w1251($tender->comment)), 0, 1, 'L');
              */         

        
        $this->ln(5);
        $this->SetFont('Verdana', 'B', $font_size);
        $this->Cell(0, $line_height, w1251('Объект строительства.'), 0, 1, 'L');
        $this->SetFont('Verdana', '', $font_size);
        $this->Cell(50, $line_height, w1251('Адрес:'), 0, 0, 'L');
        
        $this->MultiCell(0, $line_height, w1251($tender->region->name .',' .$tender->address .' ' .$tender->address_addon), 0, 'L');
        $this->Cell(50, $line_height, w1251('Название объекта:'), 0, 0, 'L');
        $this->Cell(0, $line_height, w1251($tender->address_name), 0, 1, 'L');
        $this->Cell(50, $line_height, w1251('Категория объекта:'), 0, 0, 'L');
        $this->Cell(0, $line_height, w1251($tender->category->name), 0, 1, 'L');
        
        $this->ln(5);
        $this->SetFont('Verdana', 'B', $font_size);
        $this->Cell(0, $line_height, w1251('Субъекты строительства'), 0, 1, 'L');
        $this->SetFont('Verdana', '', $font_size);
        
        foreach($tender->roles() as $role){
        
            foreach($tender->roleCustomers($role->name) as $customer) {
                $this->Cell(50, $line_height, w1251($role->title .':'), 0, 0, 'L');
                $this->Cell(0, $line_height, w1251($customer->name), 0, 1, 'L');
            }
        }
        
        
    }
}