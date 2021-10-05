<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class AcademyController extends Controller
{

    public function index()
    {
        return view('site::academy-admin.index');
    }

   
 
}