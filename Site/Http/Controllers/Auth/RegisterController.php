<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Mail\AdminSmsErrorEmail;
use ServiceBoiler\Prf\Site\Events\UserRelationCreateEvent;
use ServiceBoiler\Prf\Site\Events\UserFlRoleRequestCreateEvent;
use ServiceBoiler\Prf\Site\Http\Requests\RegisterRequest;
use ServiceBoiler\Prf\Site\Http\Requests\RegisterFlRequest;
use ServiceBoiler\Prf\Site\Http\Requests\RegisterEsbRequest;
use ServiceBoiler\Prf\Site\Http\Requests\NewPhoneRequest;
use ServiceBoiler\Prf\Site\Http\Requests\VerifyPhoneRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Models\Contact;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\ContragentType;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Engineer;
use ServiceBoiler\Prf\Site\Models\Phone;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\UserPrereg;
use ServiceBoiler\Prf\Site\Models\UserFlRoleRequest;
use ServiceBoiler\Prf\Site\Models\EsbUser;
use ServiceBoiler\Prf\Site\Services\Sms;

use Fomvasss\Dadata\Facades\DadataSuggest;
use ServiceBoiler\Prf\Site\Services\SmsBY;
use ServiceBoiler\Prf\Site\Services\Sxgeo;
use ServiceBoiler\Prf\Site\Services\IpLocation;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * @var \Geocoder\StatefulGeocoder
     */
    private $geocoder;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest')->except('confirm');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if(in_array(env('MIRROR_CONFIG'),['marketru','marketby'])) {
            return redirect()->route('e-warranty');
        }
        $authorization_types = AuthorizationType::query()->enabled()->orderBy('brand_id')->orderBy('name')->get();
        $roles = AuthorizationRole::query()->get();
        
        $countries = Country::query()->where('id', config('site.country'))->get();
        $regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();
        $address_legal_regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();
        $address_postal_regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();
        $types = ContragentType::all();

        if(env('MIRROR_CONFIG')=='sfby'){
            return view('site::auth.register_sfby', compact('countries', 'types', 'address_legal_regions', 'address_postal_regions','regions','authorization_types','roles'));
         } else {
            return view('site::auth.register', compact('countries', 'types', 'address_legal_regions', 'address_postal_regions', 'regions', 'authorization_types', 'roles'));
        }
    }
	
	/**
	* Регистрация физических лиц без контрагентов
	*/
    // Регистрация в мотивации
	public function showRegistrationFlForm()
    {   
       $ip=request()->ip();
       if(env('APP_DEBUG')){
            //$ip='80.94.224.227';
            $ip='31.40.129.210';
        }
        $res=(new IpLocation())->location($ip);

        $locality_from_ip=!empty($res['locality']) ? $res['locality'] : null;
        $region_id_from_ip=!empty($res['region_iso']) ? $res['region_iso'] : null;
        $countries = Country::query()->where('id', config('site.country'))->get();
        $address_sc_regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();


        return view('site::auth.register_fl', compact('countries', 'address_sc_regions','locality_from_ip','region_id_from_ip'));
    }
	public function showRegistrationFlsForm()
    { 
        
        return redirect()->route('register_fl');
        
        $ip=request()->ip();
        if(env('APP_DEBUG')){
            //$ip='31.40.129.210';
            $ip='80.94.224.227';
        }
        $res=(new IpLocation())->location($ip);

        $locality_from_ip=!empty($res['locality']) ? $res['locality'] : null;
        $region_id_from_ip=!empty($res['region_iso']) ? $res['region_iso'] : null;

        $countries = Country::query()->where('id', config('site.country'))->get();
        $address_sc_regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();
        
        return view('site::auth.register_fls', compact('countries', 'address_sc_regions','locality_from_ip','region_id_from_ip'));
    }
	public function showRegistrationEsbForm()
    { 
        $ip=request()->ip();
        if(env('APP_DEBUG')){
            $ip='31.40.129.210';
        }

        $res=(new IpLocation())->location($ip);
        $locality_from_ip=!empty($res['locality']) ? $res['locality'] : null;
        $region_id_from_ip=!empty($res['region_iso']) ? $res['region_iso'] : null;

        $countries = Country::query()->where('id', config('site.country'))->get();
        $address_sc_regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();
        
        return view('site::auth.register_esb', compact('countries', 'address_sc_regions','locality_from_ip','region_id_from_ip'));
    }
	
    public function showRegistrationPreregForm($guid)
    {   
        $countries = Country::query()->where('id', config('site.country'))->get();
        $address_sc_regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();
        $user_prereg = UserPrereg::query()->where('guid', $guid)->first();
        if($user_prereg) {
            $user_prereg->update(['visits'=>$user_prereg->visits+1]);
            return view('site::auth.register_prereg', compact('countries', 'address_sc_regions','user_prereg'));
        }
    }
    
	public function showRegistrationFlmForm()
    {
        $countries = Country::query()->where('id', config('site.country'))->get();
        $address_sc_regions = Region::where('country_id', config('site.country'))->orderBy('name')->get();
        
        return view('site::auth.register_flm', compact('countries', 'address_sc_regions'));
    }

    
	public function registerConfirmPhone(User $user)
    {
        if(!$user->phone_verified && $user->phone_verify_code){
            return view('site::auth.register_success', compact('user'));
        } else {
            return redirect()->route('login');
        }
    }
    
    
	public function registerVerifyPhone(User $user, VerifyPhoneRequest $request)
    {
        $user->increment('phone_verify_retry');
        if($user->phone_verified || !$user->phone_verify_code){
            return redirect()->route('login');
        } elseif($request->input('resend_sms')==1 && $user->phone_verify_retry <= 6 && $request->input('phone_verify_code')!=$user->phone_verify_code){
            $response = (new Sms())->sendSms('SendMessage',['phone'=>$user->phone,'message'=>$user->phone_verify_code]);
            if($response) {
                return redirect()->route('register_confirm_phone',$user)->with('success', 'СМС с кодом отравлено на номер ' .$user->phone);
            }else { 
           
                Mail::to([env('MAIL_DEVEL_ADDRESS')])->send(new AdminSmsErrorEmail($user->toArray()));

                $user->phone_verified=1;
                $user->save();
                Auth::login($user);
                return redirect()->route('home')->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' ошибка временная, мы уже работаем над ее устранением.');
                //return redirect()->route('register_confirm_phone',$user)->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
            }
        } elseif($user->phone_verify_retry > 6) {
            return redirect()->route('register_confirm_phone',$user)->with('error', 'Превышено количество попыток ввода кода из СМС. Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
        }
        
        if($request->input('phone_verify_code')==$user->phone_verify_code) {
            $user->phone_verified=1;
            $user->save();
            Auth::login($user);
                return redirect()->route('home')->with('success','Вы успешно зарегистрированы на сайте');
        } else {
            return redirect()->route('register_confirm_phone',$user)->with('error', 'Неверно указан код из СМС');
        }
        
    }
    
	public function registerUpdatePhone(User $user, NewPhoneRequest $request)
    {
        $user->increment('phone_verify_retry');
        if($user->phone_verified || !$user->phone_verify_code){
            return redirect()->route('login');
        } elseif($user->phone_verify_retry <= 6){
            $user->update(['phone'=>$request->input('phone')]);
            $response = (new Sms())->sendSms('SendMessage',['phone'=>$request->input('phone'),'message'=>$user->phone_verify_code]);
            if($response) {
                return redirect()->route('register_confirm_phone',$user)->with('success', 'СМС с кодом отравлено на номер ' .$user->phone);
            }else {
                return redirect()->route('register_confirm_phone',$user)->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
            }
        } elseif($user->phone_verify_retry > 6) {
            return redirect()->route('register_confirm_phone',$user)->with('error', 'Превышено количество попыток ввода кода из СМС. Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
        }
        
        
    }
    
    

    public function register(RegisterRequest $request)
    {
        
        
        $user = $this->createUser($request->all());
        $user->contacts()->save($contact = Contact::query()->create($request->input('contact')));
        $contact->phones()->save(Phone::query()->create($request->input('phone.contact')));
        $user->update(['phone_verify_code'=>mt_rand(100236, 956956)]);

        if(env('COUNTRY')=='BY'){
            $user->contragents()->save($contragent = Contragent::query()->create($request->input('contragent')));

            $legal = Address::query()->create($request->input('address.legal'));
            if (!$request->filled('legal')) {
                $postal = Address::query()->create($request->input('address.postal'));
            } else{
                $postal = Address::query()->create(array_merge($request->input('address.legal'), ['type_id' => 3]));
            }

            $contragent->addresses()->saveMany([$legal, $postal]);

            $user->attachRole(config('site.defaults.user.role_id', 2));
            $user->update(['region_id' => $legal->getAttribute('region_id')]);

        } else {
            $user->addresses()->save($actual = Address::query()->create(array_merge($request->input('address.actual'),['type_id'=>2])));
            $actual->phones()->save(Phone::create(['number'=>$request->input('address.actual.number'),'country_id'=>$request->input('phone.contact.country_id'),'extra'=>$request->input('address.actual.extra')]));
            if(in_array('3',$request->input('authorizations'))){
                $actual->update(['is_service'=>1]);
            }
            if(in_array('4',$request->input('authorizations'))){
                $actual->update(['is_shop'=>1]);
            }
            if(in_array('10',$request->input('authorizations'))){
                $user->addresses()->save($ishop = Address::query()->create(array_merge($request->input('address.actual'),['type_id'=>5])));
                $ishop->update(['is_eshop'=>1]);
            }
            if(in_array('11',$request->input('authorizations'))){
                $actual->update(['is_mounter'=>1]);
            }


            $user->contragents()->save($contragent = Contragent::query()->create($request->input('contragent')));

            $legal = Address::query()->create($request->input('address.legal'));

           if (!$request->filled('legal')) {
                $postal = Address::query()->create($request->input('address.postal'));
            } else{
                $postal = Address::query()->create(array_merge($request->input('address.legal'), ['type_id' => 3]));
            }


            $contragent->addresses()->saveMany([$legal, $postal]);

            $user->attachRole(config('site.defaults.user.role_id', 2));
            $user->update(['region_id' => $legal->getAttribute('region_id')]);

            foreach($request->input('authorizations') as $autz_id) {
                $authorization = $user->authorizations()->create(['role_id'=>$autz_id]);
                $authorization->attachTypes($request->input('authorization_types.'.$autz_id, []));
            }
        }
        event(new Registered($user));

        return redirect()->route('login')->with('success', trans('site::user.confirm_email', ['email' => $user->getEmailForPasswordReset()]));

    }
	public function register_fl(RegisterFlRequest $request)
    {
        $user = $this->createFlUser($request->except(['_token', '_method', '_create', 'users','status_id']));
        $user->update(['phone'=>$request->input('contact.phone.number')]);
        $user->update(['phone_verify_code'=>mt_rand(100236, 956956)]);
        //$user->contacts()->save($contact = Contact::query()->create($request->input('contact')));
        //$contact->phones()->save(Phone::query()->create($request->input('contact.phone')));
		$user->addresses()->save($address = Address::create($request->input('address')));
        $user->update(['region_id' => $request->input('address.region_id'), 'type_id' => '3']);
        
        //$user->attachRole($request->input('contact.role'));
        
        // если при регистрации указана компания, создаем заявку на подтверждение
        if($request->input('contact.user_id')) {
            $user->attachParent($request->input('contact.user_id'));
            $userRelation=$user->userRelationParents()->first();
            event(new UserRelationCreateEvent($userRelation));
            
        } elseif($request->input('parent_user_id')) {
            $user->attachParent($request->input('parent_user_id'));
            $userRelation=$user->userRelationParents()->first();
            event(new UserRelationCreateEvent($userRelation));
            
            
        }
        
        //для монтажников и сервисов
        // 14	montage_fl	Монтажник ФЛ
        // 15	service_fl	Сервис ФЛ
        // 16	sale_fl	Продавец ФЛ
        
        if(in_array($request->input('role_id'), ['14','15','16']))
        {
            $user->attachRole($request->input('role_id'));
        }
        
        if($request->input('role_id') && $request->input('contact.user_id')) {
           $userFlRoleRequest=$user->UserFlRoleRequests()->save(UserFlRoleRequest::query()->create(['role_id' =>$request->input('role_id')]));
           event(new UserFlRoleRequestCreateEvent($userFlRoleRequest));
           
        } 
        if($request->input('prereg_id')) {
                $user_prereg = UserPrereg::find($request->input('prereg_id'));
                if(!empty($user_prereg)){
                    if(empty($user_prereg->user_id)){
                        $user_prereg->update(['user_id'=>$user->id]);
                    }
                    
                }
            
            }
            
        if($user->email){
            if($request->input('prereg_id')) {
                $user_prereg = UserPrereg::find($request->input('prereg_id'));
                if(!empty($user_prereg)){
                    if(empty($user_prereg->user_id)){
                        $user->hasVerified();
                    }
                    
                }
            
            }
        
            event(new Registered($user));
        }
        
        
        
        
        if($user->phone && $user->phone_verified!=1) {
        
            $response = (new Sms())->sendSms('SendMessage',['phone'=>$user->phone,'message'=>$user->phone_verify_code]);
            if($response) {
                return redirect()->route('register_confirm_phone',$user)->with('success', 'СМС с кодом отравлено на номер ' .$user->phone);
            }else {
                return redirect()->route('register_confirm_phone',$user)->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
            }
            
            
        } else {
                    return redirect()->route('login')->with('success', trans('site::user.confirm_email', ['email' => $user->getEmailForPasswordReset()]));
                }
                
                

    }
	public function registerEsb(RegisterEsbRequest $request)
    {

        $user = $this->createEsbUser($request->except(['_token', '_method', '_create']));
        $user->update(['phone_verify_code'=>mt_rand(100236, 956956)]);
         $user->addresses()->create([
            'region_id'=>$request->input('region_id'),
            'locality'=>$request->input('locality'),
            'street'=>$request->input('street'),
            'building'=>$request->input('building'),
            'country_id'=>config('site.country'),
            'type_id'=>'7',
            'main' => '1',
            ]);
        $user->attachRole('41');
        event(new Registered($user));
        
        if($user->phone && $user->phone_verified!=1) {
        
            if(env('COUNTRY')=='RU'){
                $response = (new Sms())->sendSms('SendMessage',['phone'=>$user->phone,'message'=>$user->phone_verify_code]);
            }elseif(env('COUNTRY')=='BY') {
                $response = (new SmsBY())->sendSms('SendMessage',['phone'=>$user->phone,'message'=>$user->phone_verify_code]);
            }

            if($response) {
                return redirect()->route('register_confirm_phone',$user)->with('success', 'СМС с кодом отравлено на номер ' .$user->phone);
            }else {
                return redirect()->route('register_confirm_phone',$user)->with('error', 'Ошибка отправки смс на номер ' .$user->phone .' Обратитесь к администратору сайта по электронной почте: service@ferroli.ru');
            }
        } else {
                return redirect()->route('register_success',$user)->with('success', trans('site::user.confirm_email', ['email' => $user->getEmailForPasswordReset()]));
        }       
        
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function createEsbUser(array $data)
    {
        return User::create([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'middle_name'       => $data['middle_name'],
            'name'              => $data['last_name'] .' ' .$data['first_name'] .' ' .$data['middle_name'],
            'name_for_site'     => $data['last_name'] .' ' .$data['first_name'] .' ' .$data['middle_name'],
            'region_id'         => $data['region_id'],
            'mirror_id'         => env('MIRROR_CONFIG'),
            'currency_id'       => config('site.defaults.user.currency_id'),
            'price_type_id'     => config('site.defaults.user.price_type_id'),
            'warehouse_id'      => config('site.defaults.user.warehouse_id'),
            'dealer'            =>  0,
            'type_id'           =>  4,
            'email'             => $data['email'],
            'phone'             => $data['phone'],
            'password'          => Hash::make($data['password']),
        ]);
    } 
    protected function createFlUser(array $data)
    {

        return User::create([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'middle_name'       => $data['middle_name'],
            'name'              => $data['last_name'] .' ' .$data['first_name'] .' ' .$data['middle_name'],
            'name_for_site'     => $data['last_name'] .' ' .$data['first_name'] .' ' .$data['middle_name'],
            'region_id'         => $data['address']['region_id'],
            'mirror_id'         => env('MIRROR_CONFIG'),
            'currency_id'       => config('site.defaults.user.currency_id'),
            'price_type_id'     => config('site.defaults.user.price_type_id'),
            'warehouse_id'      => config('site.defaults.user.warehouse_id'),
            'dealer'            =>  0,
            'type_id'           =>  4,
            'email'             => $data['email'],
            'phone'             => $data['contact']['phone']['number'],
            'password'          => Hash::make($data['password']),
        ]);
    }
    protected function createUser(array $data)
    {
        return User::create([
            'name'          => $data['name'],
            'name_for_site' => !empty($data['name_for_site']) ? $data['name_for_site'] : $data['name'],
            'email'         => $data['email'],
            'dealer'        => isset($data['dealer']) ? 1 : 0,
            'mirror_id'         => env('MIRROR_CONFIG'),
            'currency_id'   => config('site.defaults.user.currency_id'),
            'price_type_id' => config('site.defaults.user.price_type_id'),
            'warehouse_id'  => config('site.defaults.user.warehouse_id'),
            'password'      => Hash::make($data['password']),
        ]);
    }

    public function confirm($token)
    {
        
        $user = User::whereVerifyToken($token)->firstOrFail();
        $user->hasVerified();
        //$user = User::whereVerifyToken($token)->firstOrFail()->hasVerified();
        Auth::login($user);
        if($user->created_by){
        return redirect()->route('password.create');
        } else {
            if(in_array($user->type_id,[3,4])) {
                return redirect()->route('home')->with('success', trans('site::user.confirmed_email'));
            } else {
                return redirect()->route('authorizations.index')->with('success', trans('site::user.confirmed_email'));
            }
            
        }
        //return redirect()->route('login')->with('success', trans('site::user.confirmed_email'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

}