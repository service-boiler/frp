<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Fomvasss\Dadata\Facades\DadataSuggest;

class DadataController extends Controller
{
    
    
    public function address(Request $request)
    {




        if($request->bound && $request->bound=="city") {
        $result = DadataSuggest::suggest("address", ["query"=>"$request->str", "to_bound"=>['value'=>'city'], "from_bound"=>['value'=>'city']]);
        } elseif($request->tobound && $request->tobound=="settlement") {
        $result = DadataSuggest::suggest("address", ["query"=>"$request->str", "to_bound"=>['value'=>'settlement'], "from_bound"=>['value'=>'city']]);
        } elseif($request->tobound && $request->frombound) {
         $result = DadataSuggest::suggest("address", ["query"=>"$request->str", "to_bound"=>['value'=>$request->tobound], "from_bound"=>['value'=>$request->frombound], "count"=>7]);
        } elseif($request->tobound) {
         $result = DadataSuggest::suggest("address", ["query"=>"$request->str", "to_bound"=>['value'=>$request->tobound], "count"=>7]);
        }elseif($request->frombound) {
         $result = DadataSuggest::suggest("address", ["query"=>"$request->str", "from_bound"=>['value'=>$request->frombound], "count"=>7]);
        }else{
         $result = DadataSuggest::suggest("address", ["query"=>"$request->str", "count"=>7]);
        }

        $addresses=[];
        if(!empty($result['value'])){
            if($result['data']['region_iso_code']=='UA-43')
            {
                $result['data']['region_iso_code']='RU-KRM';
            }
            if($result['data']['region_iso_code']=='UA-40')
            {
                $result['data']['region_iso_code']='RU-SVP';
            }
            $addresses[]=['id'=>$result['data']['fias_id'], 'name'=>$result['value'], 'alldata'=>$result['data']];
        } elseif($result){
            
            foreach($result as $key=>$item)
            {
                if($item['data']['region_iso_code']=='UA-43')
                {
                    $item['data']['region_iso_code']='RU-KRM';
                }
                if($item['data']['region_iso_code']=='UA-40')
                {
                    $item['data']['region_iso_code']='RU-SVP';
                }

                $addresses[]=['id'=>$item['data']['fias_id'], 'name'=>$item['value'], 'alldata'=>$item['data']];
            
            }
        }
        
        return $addresses;
     
    }
    
    public function inn(Request $request)
    {   
        
        $result = DadataSuggest::suggest("party", ["query"=>"$request->str", "to_bound"=>['value'=>'city'], "from_bound"=>['value'=>'city']]);
        $records=[];
        if(!empty($result['value'])){

        if($result['data']['address']['data']['region_iso_code']=='UA-43')
        {
                $result['data']['address']['data']['region_iso_code']='RU-KRM';
        }
        if($result['data']['address']['data']['region_iso_code']=='UA-40')
        {
                $result['data']['address']['data']['region_iso_code']='RU-SVP';
        }

        $records[]=['name'=>$result['data']['name']['full'], 'inn'=>$result['data']['inn'], 'alldata'=>$result['data']];
        } else {
        
            foreach($result as $key=>$item)
            {
             $records[]=['name'=>$item['data']['name']['full'], 'inn'=>$item['data']['inn'], 'alldata'=>$item['data']];
            
            }
        }
        
        return $records;
     
    }
    public function bank(Request $request)
    {   
        
        $result = DadataSuggest::suggest("bank", ["query"=>"$request->str", "to_bound"=>['value'=>'city'], "from_bound"=>['value'=>'city']]);
        
        $records=[];
        if(!empty($result['value'])){
        $records[]=['name'=>$result['value'], 'bic'=>$result['data']['bic'], 'alldata'=>$result['data']];
        } else {
        
            foreach($result as $key=>$item)
            {
              $records[]=['name'=>$item['value'], 'bic'=>$item['data']['bic'], 'alldata'=>$item['data']];
            
            }
        }
        
        return $records;
     
    }

    public function ip(Request $request)
    {   
        
        $result = DadataSuggest::iplocate($request->str);
        $records=[];
        if(!empty($result['value'])){
        if($result['value']['region_iso_code']=='UA-43')
        {
            $result['value']['region_iso_code']='RU-KRM';
            $result['data']['region_iso_code']='RU-KRM';
        }
        $records[]=['city'=>$result['value'], 'region_id'=>$result['data']['region_iso_code'], 'alldata'=>$result['data']];
        
        } else {
        
            foreach($result as $key=>$item)
            {
              $records[]=['city'=>$item['value'], 'region_id'=>$item['data']['region_iso_code']];
            
            }
        }
        
        
        return $records;
     
    }

   
}