<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ProfileRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ProfileEsbRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ServiceIdRequest;
use ServiceBoiler\Prf\Site\Jobs\ProcessLogo;
use ServiceBoiler\Prf\Site\Models\AcademyProgram;
use ServiceBoiler\Prf\Site\Models\AcademyPresentation;
use ServiceBoiler\Prf\Site\Models\AcademyVideo;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User as UserModel;
use ServiceBoiler\Prf\Site\Repositories\AcademyProgramRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;

class HomeController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, StoreMessages;
    
    public function __construct(AcademyProgramRepository $academyPrograms, RegionRepository $regions)
    {
        $this->academyPrograms = $academyPrograms;
        $this->regions = $regions;
    }
    /**
     * Личный кабинет пользователя
     *
     * @return \Illuminate\Http\Response
     */
    private $temp; 
     
    public function index(Request $request)
    {
        
        if (app('site')->isAdmin()) {
            return redirect()->route('admin');
        }
        $user = Auth::user();
        if ($user->hasRole('ferroli_user') || $user->hasRole('ferroli_staff')) {
            return redirect()->route('ferroli-user.home');
        }
        if ($user->type_id == '4' && env('MIRROR_CONFIG')!='kk') {
            return redirect('http://kotelkotel.ru/homeEsb');
        }
        $authorization_roles = AuthorizationRole::query()->get();
        $parent = $user->parents()->where('enabled','1')->first();
        
        if ($user->type_id == '4') {
            return view('site::home_esb', compact('user'));
        }
        
        return view('site::home', compact('user', 'authorization_roles','parent'));
    }
    public function indexEsb(Request $request)
    {
        if (app('site')->isAdmin()) {
            return redirect()->route('admin');
        }
        $user = Auth::user();
        
        if ($user->hasRole('ferroli_user') || $user->hasRole('ferroli_staff')) {
            return redirect()->route('ferroli-user.home');
        }
        
        dd(123);
        $server   = 'kotelkotel.ru';
        $port     = 1883;
        $clientId = 'php-sm';

        $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
            ->setUsername('mqttuser')
            ->setPassword('mqttpassword');
        $mqtt->connect($connectionSettings, true);
        $mqtt->publish('870449/prefferedTemp', '11', 0);
        $mqtt->disconnect();
        dd(123);
        
        return view('site::home_esb', compact('user'));
    }
    
    public function academy(Request $request)
    {
        if (app('site')->isAdmin()) {
            return redirect()->route('admin');
        }
        $user = Auth::user();
        $authorization_roles = AuthorizationRole::query()->get();
        $parent = $user->parents()->where('enabled','1')->first();
        $programs = $this->academyPrograms
                        ->applyFilter(new Filters\EnabledFilter())
                        ->all(['academy_programs.*']);
        
        return view('site::academy_ferroli.home', compact('user', 'authorization_roles','parent','programs'));
    }
    
    public function video(AcademyVideo $video)
    {   
        return view('site::academy_ferroli.home_video', compact('video'));
    }
    public function editProfile()
    {   
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $user=Auth::user();
        return view('site::profile.edit', compact('user','regions'));
    }
    public function editProfileEsb()
    {   
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $user=Auth::user();
        return view('site::profile.edit_esb', compact('user','regions'));
    }
    public function updateProfile(ProflieRequest $request)
    {   
        
        $user=Auth::user();
        $user->update([
                        'name'=>$request->input('name'),
                        'email'=>$request->input('email'),
                        'phone'=>$request->input('phone'),
                        'region_id'=>$request->input('region_id'),
        
        ]);
        
        
        return redirect()->route('home');
    }
    
    public function updateProfileEsb(ProfileEsbRequest $request)
    {   
        $user=Auth::user();
        $user->update([
                        'name'=>$request->input('last_name').' '.$request->input('first_name') .' '.$request->input('middle_name'),
                        'first_name'=>$request->input('first_name'),
                        'last_name'=>$request->input('last_name'),
                        'middle_name'=>$request->input('middle_name'),
                        'email'=>$request->input('email'),
                        'phone'=>$request->input('phone'),
                        'region_id'=>$request->input('region_id'),
        
        ]);
        if(!empty($request->input('addresses'))){
            foreach($request->input('addresses') as $address){
                $address['main']= !empty($address['main']) ? 1 : 0;
                
                if(!empty($address['id'])){ 
                    $user->addresses()->find($address['id'])->update($address);
                } else { 
                    $user->addresses()->create(array_merge($address,['country_id'=>config('site.country'),'type_id'=>'7','main' => !empty($address['main'])]));
                }
            }
        }
        
        
        try { $user->addressesActual()->whereNotIn('id',array_pluck($request->input('addresses'),'id'))->delete();
        } catch (\Exception $e){
      
            return redirect()->route('home')->with('error', 'Не удалось удалить адрес , потому что он используется в документах или справочниках Вашего оборудования');       
        }
        
         
        if($request->input('region_id') != $request->session()->get('user_region_id')) 
        {
            $request->session()->forget('user_cart_address_id');
        }
        
        $request->session()->put('user_region_id',$request->input('region_id'));
		
        
        return redirect()->route('home');
    }
    
    public function userServiceChange(UserModel $service)
    {   
        $userEsb=Auth::user();
       
        if($service->hasRole('asc')){
            
            if(($relation = $userEsb->esbServiceRelations()->where('service_id',$service->id))->exists()){
                $relation->update(['enabled'=>'1']);
            } else {
                $userEsb->esbServices()->save($service);
            }
            $userEsb->esbServiceRelations()->where('service_id','<>',$service->id)->update(['enabled'=>'0']);
            return redirect()->route('home')->with('success','Новый СЦ назначен');
        }
        return redirect()->route('home')->with('error','Новый СЦ НЕ назначен');
    }
    
    public function presentation(AcademyPresentation $presentation)
    {   
        return view('site::academy_ferroli.home_presentation', compact('presentation'));
    }
    public function slide(AcademyPresentation $presentation, $slide_num)
    {   
        return view('site::academy_ferroli.home_slide', compact('presentation','slide_num'));
    }

    /**
     * Логин под администратором
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function force(User $user, Request $request)
    {

        if (in_array($request->ip(), config('site.admin_ip'))) {
            Auth::guard()->logout();

            $request->session()->invalidate();

            Auth::login($user);

            return redirect()->route('admin');
        }


    }
    
    public function forceTemp(User $user, Request $request)
    {

            Auth::guard()->logout();
            $request->session()->invalidate();

            Auth::login($user);

            return redirect()->route('home');
        }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function logo(ImageRequest $request)
    {

        $this->authorize('create', Image::class);
        $file = $request->file('path');

        $image = new Image([
            'path'    => Storage::disk($request->input('storage'))->putFile('', new File($file->getPathName())),
            'mime'    => $file->getMimeType(),
            'storage' => $request->input('storage'),
            'size'    => $file->getSize(),
            'name'    => $file->getClientOriginalName(),
        ]);

        $image->save();
        $request->user()->image()->delete();

        $request->user()->image()->associate($image);

        $request->user()->save();

        ProcessLogo::dispatch($image, $request->input('storage'))->onQueue('images');

        return response()->json([
            'src' => Storage::disk($request->input('storage'))->url($image->path)
        ]);
    }
    
    public function message(MessageRequest $request)
    {
        return $this->storeMessage($request, Auth::user());
    }
}