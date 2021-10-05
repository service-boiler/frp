<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Engineer;
use ServiceBoiler\Prf\Site\Mail\SendTestEmail;



class TestsController extends Controller
{

    
    
    public function testService($engeneer_id, $type_id, $send = null)
    {   
        
        $engeneer = Engineer::query()
                    ->where('id', $engeneer_id)->first();
        if(!empty($engeneer->email))
        $email=$engeneer->email;
        else
        $email=$engeneer->user->email;
        $org=$engeneer->user->name;
        
        $phone=$engeneer->phone;
        $name=$engeneer->name;
        
        if(!empty($engeneer->address)) 
        $address=$engeneer->address ;
        else
        $address=$engeneer->user->addresses->where('type_id','2')->where('region_id',$engeneer->user->region_id)->first()->locality;
        
        $link=array();
        
        if($type_id=='2') {
        $linktest='https://docs.google.com/forms/d/e/1FAIpQLSfESmEnm34J96wp5hIkYyh1g6UJR2i_1_hTS2C3hjcYeNfbfA/viewform';
        $linktest=$linktest .'?entry.161167930='.$email
                        .'&entry.1429077286=' .$name
                        .'&entry.1388971351=' .$phone 
                        .'&entry.37765298=' .$address
                        .'&entry.51556039=' .$org
                        .'&entry.1614067489=' .$engeneer->id;
							
        } elseif($type_id=='1'){
        
            $link[0]='https://docs.google.com/forms/d/e/1FAIpQLSdIYQQ4GxYATBs4HydR3SO_iJ36KKB5BiG99jeRGkfM8M2mcA/viewform';
            $link[0]=$link[0] .'?entry.161167930=' .$email 
                        .'&entry.1388971351=' .$phone 
                        .'&entry.1429077286=' .$name 
                        .'&entry.1238617806=' .$engeneer->id 
                        .'&entry.37765298=' .$address 
                        .'&entry.51556039=' .$org;
            
            $link[1]='https://docs.google.com/forms/d/e/1FAIpQLSfN0zYcfqpVr8BLjvYXVR7Au8tqjwIWu4avrBCa8rq1fOAcZw/viewform';
            $link[1]=$link[1] .'?entry.161167930=' .$email 
                        .'&entry.1388971351=' .$phone 
                        .'&entry.1429077286=' .$name 
                        .'&entry.455357647=' .$engeneer->id 
                        .'&entry.37765298=' .$address 
                        .'&entry.51556039=' .$org;
            
            $link[2]='https://docs.google.com/forms/d/e/1FAIpQLSe0cyYt8-wPNOx_34RZWNugWAk7LjrL86aJLnwb5K-snOUIMQ/viewform';
            $link[2]=$link[2] .'?entry.161167930=' .$email 
                        .'&entry.1388971351=' .$phone 
                        .'&entry.1429077286=' .$name 
                        .'&entry.560633292=' .$engeneer->id 
                        .'&entry.37765298=' .$address 
                        .'&entry.51556039=' .$org;
            
            $link[3]='https://docs.google.com/forms/d/e/1FAIpQLSfggszOZL73stK-vohbid-wYGrkeZikid86Qi8NdE7CyL7bVg/viewform';
            $link[3]=$link[3] .'?entry.161167930=' .$email 
                        .'&entry.1388971351=' .$phone 
                        .'&entry.1429077286=' .$name 
                        .'&entry.1285016303=' .$engeneer->id 
                        .'&entry.37765298=' .$address 
                        .'&entry.51556039=' .$org;
            
            
            $link[4]='https://docs.google.com/forms/d/e/1FAIpQLSdccJ0GWv0LRQUteyBBCNW-QUnja6BdQfONYjk8mDQ1PwJjyg/viewform';
            $link[4]=$link[4] .'?entry.161167930=' .$email 
                        .'&entry.1388971351=' .$phone 
                        .'&entry.1429077286=' .$name 
                        .'&entry.991196891=' .$engeneer->id 
                        .'&entry.37765298=' .$address 
                        .'&entry.51556039=' .$org;
            
        
        $linktest= $link[rand(0,4)];
        }
        
        if($send=='send')
        {
        $linktest = env('APP_URL') ."/testservice/" .$engeneer->id ."/" .$type_id ."/open";
        Mail::to($email)->send(new SendTestEmail($linktest, $engeneer));
        Mail::to([env('MAIL_DEVEL_ADDRESS')])->send(new SendTestEmail($linktest, $engeneer));
        return redirect()->route('engineers.index')->with('success', trans('site::engineer.email_link_sended'));
        }
        else {        
        return redirect($linktest);
        }
    }

}   