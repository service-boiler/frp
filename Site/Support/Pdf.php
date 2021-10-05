<?php

namespace ServiceBoiler\Prf\Site\Support;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Database\Eloquent\Model;

abstract class Pdf extends Fpdf
{
    protected $model;

    protected $defaults = [
        'font_size' => 9,
        'font_size_small' => 7,
        'line_height' => 5,
    ];

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    function Footer()
    {
        $this->SetY(-10);
       // $this->AddFont('verdana', '', 'verdana.php');
        $this->SetFont('','',6);
        if(empty($this->type)) {
        $this->write(3,w1251('Документ создан в автоматическом режиме на сайте https://service.ferroli.ru. Документ явлеется коммерческой тайной и предназначен исключительно для лиц и представителей компаний, указанных в документе.'));
        } else {
        $this->write(3,w1251('Документ создан в автоматическом режиме на сайте https://service.ferroli.ru. Действительность может быть проверена запросом на электронную почту service@ferroli.ru'));
        }
    }

    public function render()
    {
        $this->SetFillColor(255, 255, 255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetMargins(10, 10, 10);
        $this->AddFont('verdana', '', 'verdana.php');
        $this->AddFont('verdana', 'B', 'verdanab.php');
        $this->AddFont('verdana', 'I', 'verdanai.php');
        $this->AddFont('verdana', 'U', 'verdanaz.php');
                
        $this->build();
        return response($this->Output('ferroli.pdf','D'), 200)->header('Content-Type', 'application/pdf');
    }
    
    public function store($storage)
    {
        $this->SetFillColor(255, 255, 255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetMargins(10, 10, 10);
        $this->AddFont('verdana', '', 'verdana.php');
        $this->AddFont('verdana', 'B', 'verdanab.php');
        $this->AddFont('verdana', 'I', 'verdanai.php');
        $this->AddFont('verdana', 'U', 'verdanaz.php');
                
        $this->build();
        $tender=$this->model;
        $file=$this->Output("./storage/tenders/tenders_" .$tender->id .'_' .$tender->created_at->format('Y-m-d') ."_rendered.pdf", "F");
        return "./storage/tenders/tenders_" .$tender->id .'_' .$tender->created_at->format('Y-m-d') ."_rendered.pdf";
    }

    abstract function build();
}