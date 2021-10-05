<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('site::auth.login');
    }

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectTo()
    {
        return app('site')->isAdmin() ? route('index') : route('home');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {      
        
        $this->validate($request, [
            // 'phone' => 'required_without:email',
            // 'email' => 'required_without:phone',
            'login' => 'required',
            'password'        => 'required|string',
            'captcha'         => 'required|captcha',
        ], [
            'captcha' => trans('site::register.error.captcha'),
            'phone' => 'телефон',
            'email' => 'е-маил'
        ]);
    }

    protected function credentials(Request $request)
    { 
        return array_merge($request->only($this->username(), 'password'), ['active' => 1]);
    }

}