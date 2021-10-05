<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $user = Auth::user();
        if ($user->hasRole('ferroli_user') && !app('site')->isAdmin()) {
            return redirect()->route('ferroli-user.home');
        } else {
        return view('site::admin.index');
        }
    }
}