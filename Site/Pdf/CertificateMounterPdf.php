<?php

namespace ServiceBoiler\Prf\Site\Pdf;

use ServiceBoiler\Prf\Site\Support\Pdf;

class CertificateMounterPdf extends Pdf
{

    function build()
    {
        $font_size = $this->defaults['font_size'];
        $line_height = $this->defaults['line_height'];
        $this->AddPage();
		$this->SetFont('Verdana', '', '15');
        $this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/cert-m-ru-1.jpg',0,0,210);
		$this->ln(30);
        $this->Cell(300, $line_height, w1251('№ ' .$this->model->id), 0, 1, 'C');
		$this->ln(55);
		$this->SetFont('Verdana', '', '22');
		$this->ln(1);
        
        if(!empty($this->model->user)) {
            $this->Cell(0, $line_height, w1251($this->model->user->name), 0, 1, 'C');
            $this->ln(5);
            if(!empty($this->model->user->acceptedParents->first())) {
                $this->Cell(0, $line_height, w1251($this->model->user->acceptedParents->first()->name), 0, 1, 'C');
                $this->ln(5);
                $this->Cell(0, $line_height, w1251($this->model->user->acceptedParents->first()->addresses->first()->locality), 0, 1, 'C');
            } else {
                $this->Cell(0, $line_height, w1251(''), 0, 1, 'C');
                $this->ln(5);
                $this->Cell(0, $line_height, w1251($this->model->user->addresses->first()->locality), 0, 1, 'C');
            
            }    
            
        } else {
            $this->Cell(0, $line_height, w1251($this->model->engineer->name), 0, 1, 'C');
            $this->ln(5);
            $this->Cell(0, $line_height, w1251($this->model->engineer->user->name), 0, 1, 'C');
            $this->ln(5);
            $this->Cell(0, $line_height, w1251($this->model->engineer->user->addresses->first()->locality), 0, 1, 'C');
        }
        
        
        $this->ln(108);
		$this->SetFont('Verdana', 'B', '11');
		$this->Cell(163, $line_height, w1251($this->model->created_at->format('d.m.Y')), 0, 1, 'C');
		
		/**			
		$this->AddPage('L');
        $this->SetFont('Verdana', '', 15);
		$this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/cert-m-ru-1.jpg',0,0,210);
        $this->ln(70);
		$this->Cell(0, 30, w1251(trans('site::certificate.pdf.number', [
            'number' => $this->model->id,
            'date'   => $this->model->created_at->format('d.m.Y')
        ])), 0, 1, 'C');
        $this->SetFont('Verdana', 'B', 25);
        $this->Cell(0, 30, w1251($this->model->engineer->user->name), 0, 1, 'C');
		**/
		
    }
}