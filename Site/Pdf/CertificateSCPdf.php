<?php

namespace ServiceBoiler\Prf\Site\Pdf;

use ServiceBoiler\Prf\Site\Support\Pdf;

class CertificateSCPdf extends Pdf
{

    function build()
    {
        $contragent=$this->model->contragents()->whereNotNull('contract')->orderByDesc('created_at')->first();
        $cert_num = substr(explode(" ",$contragent->contract)[0], 2,7);
        $font_size = $this->defaults['font_size'];
        $line_height = $this->defaults['line_height'];
        $this->AddPage();
		$this->SetFont('Verdana', '', '15');
        if($this->type=='sc_ferroli') {
            $this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/cert-ferroli-ru-sc.jpg',0,0,210);
            $brand='FERROLI';
        } elseif($this->type=='sc_lambo') {
            $this->Image('/var/ssd0/www/service.ferroli.ru/storage/app/files/lambo-service.jpg',0,0,210);
            $brand='LAMBORGINI';
        }
		$this->ln(40);
        
        $this->Cell(300, $line_height, w1251('№ ' .$cert_num), 0, 1, 'C');
		$this->ln(55);
        
        $this->Cell(0, $line_height,w1251("Настоящий сертификат удостоверяет, что"),0,1,'C');
		$this->SetFont('Verdana', '', '22');
		$this->ln(5);
        
            $this->MultiCell(0, 9, w1251($contragent->name), 0, 'C');
            $this->ln(5);
            $this->Cell(0, $line_height, w1251($contragent->addresses->first()->locality), 0, 1, 'C');
            
        $this->SetFont('Verdana', '', '13');
		$this->ln(5);
        $this->Cell(0, $line_height, w1251("является авторизованным сервисным центром по оборудованию "), 0, 1, 'C');
        $this->Cell(0, $line_height, w1251($brand ." на основании договора №" .$contragent->contract), 0, 1, 'C');
        $this->Cell(0, $line_height, w1251("и уполномочен проводить работы по вводу в эксплуатацию, гарантийному и "), 0, 1, 'C');
        $this->Cell(0, $line_height, w1251("послегарантийному ремонту и техническому обслуживанию "), 0, 1, 'C');
        $this->Cell(0, $line_height, w1251("котельного оборудования торговой марки " .$brand ."."), 0, 1, 'C');
        $this->ln(30);
        $this->Cell(0, $line_height, w1251("Срок действия сертификата до 31 декабря 2021г."), 0, 1, 'C');
        
        $this->ln(20);
        
        $this->SetFont('Verdana', '', '11');
        $this->Cell(0, $line_height, w1251("Начальник отдела по работе с клиентами, обслуживания"), 0, 1, 'L');
        $this->Cell(0, $line_height, w1251("и администрирования ООО «Ферроли Рус»"), 0, 1, 'L');
        $this->ln(10);
        $this->Cell(0, $line_height, w1251("Матвеева М.К."), 0, 1, 'L');

        
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