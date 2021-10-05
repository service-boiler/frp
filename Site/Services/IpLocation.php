<?php

namespace ServiceBoiler\Prf\Site\Services;

use Illuminate\Http\Response;
use ServiceBoiler\Prf\Site\Models\CacheIpLocation;
use Fomvasss\Dadata\Facades\DadataSuggest;
use ServiceBoiler\Prf\Site\Services\Sxgeo;

class IpLocation
{

    public function location($ipAddress)
	{

        $cache=CacheIpLocation::where('ip',$ipAddress)->first();
        if(!empty($cache)){
            $location['locality'] = $cache->city;
            $location['region_iso'] = $cache->region_iso;
            $location['country_iso'] = $cache->country_iso;
            return $location;
        }

	    if(env('COUNTRY')=='RU') {
            $result = DadataSuggest::iplocate($ipAddress);

            if (!empty($result['data'])) {
                if ($result['data']['region_iso_code'] == 'UA-43') {
                    $result['data']['region_iso_code'] = 'RU-KRM';
                }
                $location['locality']= $result['data']['city_with_type'] ? $result['data']['city_with_type'] : $result['data']['settlement_with_type'];
                $location['region_iso'] = $result['data']['region_iso_code'];
                $location['country_iso'] = $result['data']['country_iso_code'];

                CacheIpLocation::create(['ip'=>$ipAddress, 'country_iso'=>$location['country_iso'],'region_iso'=>$location['region_iso'],'city'=>$location['locality']]);

                return $location;
            }

        } elseif(env('COUNTRY')=='BY'){
            $result = (new Sxgeo())->location($ipAddress);
            if (!empty($result['region'])) {
                $location['locality']=$result['city']['name_ru'] ? $result['city']['name_ru'] : '';
                $location['region_iso'] = $result['region']['iso'];
                $location['country_iso'] = $result['country']['iso'];
                CacheIpLocation::create(['ip'=>$ipAddress, 'country_iso'=>$location['country_iso'],'region_iso'=>$location['region_iso'],'city'=>$location['locality']]);
                return $location;
            }
        }
	}


}