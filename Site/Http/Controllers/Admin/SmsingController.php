<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Services\Sms;
use Illuminate\Support\Facades\URL;

use ServiceBoiler\Prf\Site\Filters\FerroliManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\User\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserDoesntHaveUnsubscribeFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserVerifiedFilter;
use ServiceBoiler\Prf\Site\Filters\User\ActiveSelectFilter;

use ServiceBoiler\Prf\Site\Http\Requests\Admin\SmsingSendRequest;

use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\QueueSms;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;

class SmsingController extends Controller
{

    private $users;
    
    
    public function __construct(UserRepository $users)
    {
        $this->users = $users;

    }

    /**
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $headers = collect([
            trans('site::user.name'),
            trans('site::address.full'),
        ]);

        $phones = collect([]);
        $this->users->trackFilter();
       // $this->users->applyFilter(new FerroliManagerAttachFilter);
        $this->users->applyFilter(new UserNotAdminFilter);
        $this->users->applyFilter(new ActiveSelectFilter);
        $this->users->pushTrackFilter(RegionFilter::class);
        $this->users->pushTrackFilter(UserRoleFilter::class);
        $this->users->pushTrackFilter(ActiveSelectFilter::class);
        $repository = $this->users;
        $duplicates = collect([]);
        
        /** @var User $user */
        foreach ($this->users->all() as $user) {
            if ($user->getAttribute('phone') && $duplicates->search($user->getAttribute('phone')) === false) {
                $phones->push([
                    'number'    => $user->getAttribute('phone'),
                    'verified' => $user->getAttribute('phone_verified'),
                    'active' => $user->getAttribute('active'),
                    'extra'    => [
                        'name'    => $user->getAttribute('name'),
                        'address' => '',
                    ],
                ]);
                $duplicates->push($user->getAttribute('phone'));
            }
            
            foreach ($user->contacts()->get() as $contact) {
            
                if($contact->phones()->where('number','like','9%')->exists()){
                    foreach($contact->phones()->where('number','like','9%')->get() as $phone) {
                        if ($duplicates->search($phone->number) === false) {
                            $phones->push([
                                'number'    => preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $phone->number),
                                'verified' => false,
                                'active' => $user->getAttribute('active'),
                                'extra'    => [
                                    'name'    => $user->getAttribute('name') ." - " .$contact->getAttribute('name'),
                                    'address' => '',
                                ],
                            ]);
                            $duplicates->push($phone->number);
                        }
                        
                    
                    }
                
                }
                
            
            }

        }
        $route = route('admin.users.index');
        
        return view('site::admin.mailing.sms_create', compact('headers', 'phones', 'route', 'repository'));
    }

    public function store(SmsingSendRequest $request)
    {

        // $errors = array();
        // foreach ($request->input('recipient') as $phone) {
        // try {
             // $response = (new Sms())->sendSms('SendMessage',['phone'=>$phone,'message'=>$request->input('content')]);
        // } catch (\Exception $e) {
            // $errors[] = $email;
        // } 
        
        // }
        foreach ($request->input('recipient') as $phone) {
            QueueSms::create(['phone'=>$phone,
                             'text'=>$request->input('content'),
                             'bulk'=>1,
                             'bulk_id'=>time(),
                             'creator_id'=>auth()->user()->id]);
            
            
            }
        
       
        return redirect()->back()->with('success', 'Рассылка поставлена в очередь на отправку. Сообщения будут отправлены в течение 5-60 минут');
       
    }
}
