<?php

namespace ServiceBoiler\Prf\Site\Pdf;


use ServiceBoiler\Prf\Site\Support\Pdf;

class RevisionPartPdf extends Pdf
{

    function build()
    {
        $revisionPart=$this->model;
        
        $font_size = $this->defaults['font_size'];
        $font_size_small = $this->defaults['font_size_small'];
        $line_height = $this->defaults['line_height'];
        $this->AddPage();
        $this->SetLeftMargin(10);
        $this->SetRightMargin(10);
        $this->SetFont('Verdana', '', $font_size);
        $this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/logo.jpg',15,10,30);
        
        $this->SetFont('Verdana', '', '12');
        $this->Cell(50, $line_height*2,' ' , 0, 0, 'L');
        $this->Cell(130, $line_height*2, w1251('ТЕХНИЧЕСКАЯ ИНФОРМАЦИЯ. ИСХ №' .$revisionPart->id), 1, 1, 'L');
        $this->SetFillColor(240,240,240);
        $this->Cell(50, $line_height,' ' , 0, 0, 'L');
        $this->Cell(65, $line_height*2, w1251('Дата письма: '.$revisionPart->date_notice->format('Y.m.d')), 1, 0, 'L');
        $this->Cell(65, $line_height*2, w1251('Дата изменений: '.$revisionPart->date_change->format('Y.m.d')), 1, 1, 'L');
        $this->Cell(0, $line_height, '', 0, 1, 'L');
        
        $this->Cell(50, $line_height,w1251('Предмет изменения') , 0, 0, 'L');
        $this->MultiCell(130, $line_height, w1251($revisionPart->text_object), 'L', 1, 'L',true);
        
        
        $this->Cell(0, $line_height*0.5, '', 0, 1, 'L');
        
        $this->Cell(50, $line_height,w1251('Описание изменений') , 0, 0, 'L');
        
        $this->MultiCell(130, $line_height, w1251($revisionPart->description), 'L', 1, 'L', true);
        
        $this->Cell(0, $line_height*0.5, '', 0, 1, 'L');
        
        $this->Cell(50, $line_height,w1251('Старый артикул') , 0, 0, 'L');
        $this->MultiCell(130, $line_height, w1251($revisionPart->part_old_sku), 'L', 1, 'L', true);
        
        $this->Cell(0, $line_height*0.5, '', 0, 1, 'L');
        
        $this->Cell(50, $line_height,w1251('Новый артикул') , 0, 0, 'L');
        $this->SetFont('Verdana', 'B', 12);
        $this->Cell(130, $line_height, w1251($revisionPart->part_new_sku .' ' .$revisionPart->partNew->name), 'L', 1, 'L', true);
        $this->SetFont('Verdana', '', 12);
        
        $this->Cell(0, $line_height*0.3, '', 0, 1, 'L');
        
        $this->Cell(50, 50,w1251('ФОТО '.$revisionPart->part_new_sku), 'R', 0, 'L');
        $this->Cell(1, 50,'', 'L', 0, 'L');
        $this->Image($revisionPart->partNew->images()->first()->pathFull(),null,null,50);
        $this->Cell(0, $line_height*0.3, '', 0, 1, 'L');
        $this->SetFillColor(255,255,255);
         $this->Cell(100, $line_height*2,w1251('Входит в оборудование') , '1', 0, 'L');
         $this->Cell(45, $line_height*2,w1251('Введено с сер. №') , '1', 0, 'L');
         $this->MultiCell(35, $line_height,w1251('Взаимоза- меняемость') , 1, 1, 'С', false);
        
        $this->SetFillColor(240,240,240);
        foreach($revisionPart->products as $product) {
        
         $this->Cell(100, $line_height*1.5,w1251($product->name) , 1, 0, 'L', true);
         $this->Cell(45, $line_height*1.5,w1251($product->pivot->start_serial) , 1, 0, 'L', true);
         $this->Cell(35, $line_height*1.5,w1251(trans('site::admin.revision_part.interchange_'.($revisionPart->interchange))) , 1, 1, 'С', true);
        
        }
            
        
        
    }
}