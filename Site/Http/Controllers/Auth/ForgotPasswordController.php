<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use ServiceBoiler\Prf\Site\Http\Requests\SmsRequest;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Services\Sms;
use ServiceBoiler\Prf\Site\Http\Requests\PasswordSmsRequest;

class ForgotPasswordController extends Controller
{

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Показать форму для сброса пароля
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('site::auth.passwords.email');
    }
    
    public function resetSmsPassword(PasswordSmsRequest $request, User $user)
    {
        $user->increment('phone_verify_retry');
        
        if($user->phone_verify_retry <= 6 && $request->input('phone_verify_code')==$user->phone_verify_code) {
            $user->phone_verified=1;
            $user->phone_verify_retry=1;
            $user->phone_verify_code=null;
            $user->save();
             $user->update([
                    'password' => Hash::make($request->input('password')),
            ]);
            Auth::login($user);
                return redirect()->route('home')->with('success','Пароль изменен успешно');
        } elseif($user->phone_verify_retry <= 6) {
            return redirect()->route('password.confirm_phone',$user)->with('error', 'Неверно указан код из СМС');
        } else {
                return redirect()->route('password.confirm_phone',$user)->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
            }
        return redirect()->route('password.confirm_phone',$user)->with('error', 'Неверно указан код из СМС');
    }
    
    
    public function resendSmsPassword(User $user)
    {
        $user->increment('phone_verify_retry');
        if($user->phone_verify_retry <= 6 && $user->phone_verify_code){
            $response = (new Sms())->sendSms('SendMessage',['phone'=>$user->phone,'message'=>$user->phone_verify_code]);
            if($response) {
                return redirect()->route('password.confirm_phone',$user)->with('success', 'СМС с кодом отравлено на номер ' .$user->phone);
            }else {
                return redirect()->route('password.confirm_phone',$user)->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
            }
        } else {
            return redirect()->route('password.confirm_phone',$user)->with('error', 'Превышено количество попыток ввода кода из СМС. 
                                                        Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
        }
        
    }
    
    public function confirmPhone(User $user)
    {
        if($user->phone_verify_code) {
            return view('site::auth.confirm_phone',compact('user'));
        } else {
            return redirect()->route('login')->with('error', 'СМС не отправлено. Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
        }
        
    }
    
    
    public function sendResetSmsEmail(SmsRequest $request)
    {
        if (!User::where('phone',preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $request->input('phone')))->exists()) {
               sleep(5);
               return redirect()->route('password.request')->with('error','Пользователь с телефоном '.$request->input('phone') .' не зарегистрирован');
        }
        else {
            $user=User::where('phone',preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $request->input('phone')))->first();
            $user->update(['phone_verify_code'=>mt_rand(100236, 956956)]);
            $response = (new Sms())->sendSms('SendMessage',['phone'=>$user->phone,'message'=>$user->phone_verify_code]);
            if($response) {
                return redirect()->route('password.confirm_phone',$user)->with('success', 'СМС с кодом отравлено на номер ' .$user->phone);
            }else {
                return redirect()->route('password.request',$user)->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
            }
        
        }
        
        return view('site::auth.passwords.email');
    }

    /**
     * Получить ответ при успешном сбросе пароля
     *
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return redirect()->route('login')->with('success', trans($response));
    }

}