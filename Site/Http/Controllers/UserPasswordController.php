<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\UserPasswordRequest;
use ServiceBoiler\Prf\Site\Models\User;

class UserPasswordController extends Controller
{

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        return view('site::auth.password_create', compact('user'));
    }

    /**
     * @param UserPasswordRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserPasswordRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('home')->with('success', trans('site::user.password_updated'));
    }

}