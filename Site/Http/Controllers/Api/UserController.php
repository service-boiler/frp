<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\User\RegisterUserSearchFilter;
use ServiceBoiler\Prf\Site\Filters\User\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserIsNotFlFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserActiveFilter;
use ServiceBoiler\Prf\Site\Filters\User\HasDisributorsRolesFilter;
use ServiceBoiler\Prf\Site\Filters\UserSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Event\UserNotInEventParticipantFilter;
use ServiceBoiler\Prf\Site\Http\Resources\UserResource;
use ServiceBoiler\Prf\Site\Http\Resources\UserCollection;
use ServiceBoiler\Prf\Site\Http\Resources\UserSearchCollection;
use ServiceBoiler\Prf\Site\Http\Resources\UserRegisterCollection;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;

class UserController extends Controller
{

    protected $users;
    

    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $products
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    

    public function index()
    {   $result = collect([]);
        $this->users->applyFilter(new UserNotAdminFilter);
        $this->users->applyFilter(new UserIsNotFlFilter);
        $this->users->applyFilter(new UserActiveFilter);
        $this->users->applyFilter(new RegisterUserSearchFilter());
        $this->users->applyFilter(new RegionFilter());
        
        
        $users = new UserRegisterCollection($this->users->all());
      
        $resize = $users->take(10);
        
        foreach ($resize as $user) 
                {
                    if(($addresses = $user->addresses()->where('type_id', 2)->get())->isNotEmpty()) {
                    $locality=$addresses[0]->locality;                               
                        } else {$locality='';
                        }
                                     
					$result->push([
						'id' => $user->id,
						'name' => $user->name,
						'region_name' => $user->region->name,
						'region_id' => $user->region_id,
                        'locality' => $locality,
                        
					]);
                    

				}
        
        
      
        return $result;
      
      
    }


    /**
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }
    
    public function search(Request $request)
    {   
        
        $this->users->applyFilter(new RegisterUserSearchFilter());
        $this->users->applyFilter((new UserNotInEventParticipantFilter()));
        if($request->filled('filter.has_distr_roles') && $request->filter['has_distr_roles']=='1') {
        $this->users->applyFilter((new HasDisributorsRolesFilter()));
        }
        $users = new UserSearchCollection($this->users->all());
       
        return $users;    
        
    }
    
    public function createMisssion (User $user)
    {  
        
        return view('site::admin.mission.create_user', compact('user'));
         
    }
    
    
    public function phoneExists(Request $request)
    {
        $phone=preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $request->phone);
        if(substr($phone,0,1)!='9'){return ['error'=>'no_mobile'];
        }
        return ['exists'=>User::where('phone',$phone)->exists()];
    }
    
    public function emailExists(Request $request)
    {
        return ['exists'=>User::where('email', $request->email)->exists()];
    }
}