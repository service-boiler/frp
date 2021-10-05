<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use ServiceBoiler\Prf\Site\Support\Word;

class SflWord extends Word
{

    function build()
    {

        $template = Storage::disk('templates')->url($this->fileName);
        $this->templateProcessor = new TemplateProcessor($template);
        $this->templateProcessor->setValue('ID', 'Иванов');
        //$template->setValue('Name', 'Иванов'); //Производим замену метки на значение
    }
}
