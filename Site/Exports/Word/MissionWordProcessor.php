<?php

namespace ServiceBoiler\Prf\Site\Exports\Word;

use PhpOffice\PhpWord\TemplateProcessor;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionWordProcessor
{

    protected $mission;

    protected $templateProcessor;

    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
    }

    public function render()
    {
        $this->build();
        $this->_checkoutput();
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $this->mission->templateFile()->name .' №' .$this->mission->id .' ' .$this->mission->date_from->format('d.m.Y') .' ' .$this->mission->users()->where('main',1)->first()->name . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $this->templateProcessor->saveAs('php://output');
        exit();
    }


    protected function _checkoutput()
    {

        if (PHP_SAPI != 'cli') {
            if (headers_sent($filename, $linenum)) {
                echo "Невозможно создать docx файл, т.к. заголовки уже были отправлены в {$filename} в строке {$linenum}";
                exit();
            }
        }
        if (ob_get_length()) {
            // The output buffer is not empty
            if (preg_match('/^(\xEF\xBB\xBF)?\s*$/', ob_get_contents())) {
                // It contains only a UTF-8 BOM and/or whitespace, let's clean it
                ob_clean();
            } else {
                echo "Невозможно создать docx файл, т.к. заголовки уже были отправлены";
                exit();
            }

        }
    }
    
    
    
    function build()
    {

        $this->templateProcessor = new TemplateProcessor($this->mission->templateFile()->file->src());
    if($this->mission->users()->where('main',1)->exists()) {
        $this->templateProcessor->setValue('user_name', $this->mission->users()->where('main',1)->first()->name);
        $this->templateProcessor->setValue('position', $this->mission->users()->where('main',1)->first()->company_position ? $this->mission->users()->where('main',1)->first()->company_position : 'не указана');
    } else {
        $this->templateProcessor->setValue('user_name', 'Ответственный не назначен');
        $this->templateProcessor->setValue('position', 'Ответственный не назначен');

    }
        $this->templateProcessor->setValue('region_id', $this->mission->region->name);
        $this->templateProcessor->setValue('locality', $this->mission->locality);
        $this->templateProcessor->setValue('date_from', $this->mission->date_from->format('d.m.Y'));
        $this->templateProcessor->setValue('date_to', $this->mission->date_to->format('d.m.Y'));
        if($this->mission->missionClients()->exists()) {
            $clients='Посещение партнеров: ';
            foreach($this->mission->missionClients as $client){
                $clients.=$client->user->name .', ';
            }
        } else {
            $clients='';
        }
        $this->templateProcessor->setValue('target', $this->mission->target .$clients);
        $this->templateProcessor->setValue('result', $this->mission->result);

    }
}