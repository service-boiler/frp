<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Tender;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderStatusChangeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Tender
     */
    public $tender;
    public $files;
    /**
     * Create a new message instance.
     * @param Tender $tender
     * @param null $adminMessage
     */
    public function __construct(Tender $tender, array $files= [])
    {
        $this->tender = $tender;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        switch($this->tender->status->id) {
            case 2:
            case 3:
                return $this->subject('Новый тендер на согласование')->view('site::email.admin.tender.status');
                break;
            case 4:
                return $this->subject('Тендер №' .$this->tender->id .'. согласован директором')
                            ->view('site::email.admin.tender.status')
                            ->embedFiles();
                break;
            case 5:
                return $this->subject('Тендер №' .$this->tender->id .'. принят к выполнению')->view('site::email.admin.tender.status');
                break;
            case 6:
                return $this->subject('Тендер №' .$this->tender->id .'. успешно завершен')->view('site::email.admin.tender.status');
                break;
            case 10:
            case 7:
                return $this->subject('Тендер №' .$this->tender->id .'. отменен')->view('site::email.admin.tender.status');
                break;
            case 8:
                return $this->subject('Тендер №' .$this->tender->id .'. удален')->view('site::email.admin.tender.status');
                break;
            case 9:
                return $this->subject('Тендер №' .$this->tender->id .' на доработку')->view('site::email.admin.tender.status');
                break;
            default:
                return $this->subject('Тендер №' .$this->tender->id .'. Статус изменен на ' .$this->tender->status->name)->view('site::email.admin.tender.status');
                break;
        }
    }
    private function embedFiles()
    {
        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $this->attach($file['file'], $file['options']);
            }
        }

        return $this;
    }
}
