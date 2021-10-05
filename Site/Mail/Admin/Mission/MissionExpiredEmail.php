<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Mission;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionExpiredEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
  
    public $mission;

    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
    }

     public function build()
    {
        return $this->subject('Командировка №' .$this->mission->id .'. Не заверена!')->view('site::email.admin.mission.expired');
   }
}
