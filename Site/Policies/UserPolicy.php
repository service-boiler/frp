<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;

class UserPolicy
{

    public function schedule(User $user, User $export_user)
    {
        return ($user->admin == 1|| $user->hasPermission('admin_shedule')) 
                && $export_user->can_schedule() 
                && $export_user->contragents()->exists()
                && $export_user->addresses()->where('type_id',2)->exists();
    }


    public function buy(User $user)
    {
        return $user->hasPermission('buy');
    }

    public function product_list(User $user)
    {
        return $user->hasPermission('product.list');
    }

    public function equipment_list(User $user)
    {
        return $user->hasPermission('equipment.list');
    }
    
    public function retail_sale_report_create(User $user)
    {
        return !empty($user->acceptedParents->first()) && 
          ($user->parents->first()->hasRole('dealer') || $user->parents->first()->hasRole('distr') || $user->parents->first()->hasRole('gendistr'))
          && $user->hasRole('sale_fl');
    }
    
    public function view(User $manager, User $user)
    {   
        return (in_array($user->region_id,$manager->notifiRegions->pluck('id')->toArray()) && !$user->hasRole('ferroli_user')&& $user->email!=config('site::director_email')) 
                || $user->created_by == $manager->id 
                || $manager->admin == 1  || $manager->hasPermission('admin_users_view')
                || ($manager->hasRole('supervisor') && !in_array($user->email,config('site.director_email')) && $user->admin != 1);
    }
    public function force_login(User $manager, User $user)
    {   
        return ( $manager->admin == 1  
                || in_array($manager->email,config('site.director_email'))
                || (($manager->hasRole('supervisor') || $manager->hasRole('service_super')|| $manager->hasRole('ferroli_user')) 
                && !in_array($user->email,config('site.director_email')) && $user->admin != 1));
    }
    public function user_price(User $manager, User $user)
    {   
        return ( $manager->admin == 1  || $manager->hasRole('admin_site')  || $manager->hasRole('supervisor') ||  $manager->hasRole('service_super'));
    }

}
