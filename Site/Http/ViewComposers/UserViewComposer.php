<?php

namespace ServiceBoiler\Prf\Site\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use ServiceBoiler\Prf\Site\Models\User;

class UserViewComposer
{
    public function compose(View $view)
    {
        $user = request()->user();
        $view->with('current_user', $user);
        if(in_array(request()->ip(), config('site.admin_ip')) && !array_key_exists(optional($user)->id, config('site.admin_ip'))){
            $admin_id = array_search(request()->ip(), config('site.admin_ip'));
            $admin = User::query()->find($admin_id);
            $view->with('current_admin', $admin);
        }
    }
}