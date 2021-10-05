<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Rbac\Repositories\RoleRepository;
use ServiceBoiler\Prf\Site\Events\UserRelationCreateEvent;
use ServiceBoiler\Prf\Site\Events\UserFlRoleRequestCreateEvent;
use ServiceBoiler\Prf\Site\Http\Requests\UserRelationRequest;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\UserRelation;
use ServiceBoiler\Prf\Site\Models\UserFlRoleRequest;

class UserRelationController extends Controller
{   
    protected $roles;

    public function __construct(
		RoleRepository $roles
		)
	{
		$this->roles = $roles;
	}

    public function index(UserRelationRequest $request)
    {
        $user = $request->user();
        $userRelationChilds = $user->userRelationChilds->where('enabled','1');
        $userRelationParents = $user->userRelationParents->where('enabled','1');
        
        $roles_fl = $this->roles->all()->where('role_fl','1');
        

        return view('site::user_relations.index', compact('user','userRelationChilds','userRelationParents','roles_fl'));
    }


    public function create(UserRelationRequest $request)
    {  
       if($request->input('role')) {
       $userFlRoleRequest=$request->user()->UserFlRoleRequests()->save(UserFlRoleRequest::query()->create(['role_id' =>$request->input('role')]));
       event(new UserFlRoleRequestCreateEvent($userFlRoleRequest));
       
       }
       elseif($request->input('contact.user_id')) {
       $request->user()->attachParent($request->input('contact.user_id'));
       $UserRelation=$request->user()->userRelationParents()->orderByDesc('id')->first();
       event(new UserRelationCreateEvent($UserRelation));
       
       }
       else {
       return redirect()->route('user_relations.index')->with('error', trans('site::user.relation_request_empty'));
       }
       return redirect()->route('user_relations.index')->with('success', trans('site::user.relation_request_sended'));
    }

    public function update(UserRelationRequest $request, UserRelation $userRelation)
    {
        $updateMessage='';
        $this->authorize('edit', $userRelation);
        
        if(!empty($request->input('userRelation'))) {
            $userRelation->update($request->input('userRelation'));
            
        }
        
        if($request->input('userRelation.accepted')==1){
            $userRelation->update(['enabled'=>'1']);
            
            if(!empty($userRelation->child->certificates) &&
                ($userRelation->child->hasRole('service_fl') || $userRelation->child->hasRole('montage_fl'))){
                $lastName = explode(' ',$userRelation->child->name)[0];
                $firstName = explode(' ',$userRelation->child->name)[1]; 
                
                if(empty($userRelation->parent->engineers()->where('email',$userRelation->child->email)->count())){
                
                    if(!empty($userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->count())) {
                        $userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')
                            ->update(['fl_user_id'=>$userRelation->child->id]);
                        $userRelation->child->certificates()
                            ->update(['engineer_id'=>$userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->orderByDesc('created_at')->first()->id]);
                        
                    }
                

                    if(empty($userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->count()) && 
                        empty($userRelation->parent->engineers()->where('email',$userRelation->child->email)->count())){
                
                            $createdEngineer=$userRelation->parent->engineers()->create(['user_id'=>$userRelation->parent->id,
                                                                            'fl_user_id'=>$userRelation->child->id, 
                                                                            'name'=>$userRelation->child->name, 
                                                                            'email'=>$userRelation->child->email, 
                                                                            'address'=>$userRelation->child->addresses->first()->region->name .", " .$userRelation->child->addresses->first()->locality, 
                                                                            'phone'=>$userRelation->child->contacts->first()->phones->first()->number]);
                            $userRelation->child->certificates()
                                ->update(['engineer_id'=>$createdEngineer->id]);
                            $updateMessage = $createdEngineer->name .' ' .trans('site::messages.created') .' ' .trans('site::messages.success');
               
                    }
                } else {
                    $updatedEngineers=$userRelation->parent->engineers()->where('email',$userRelation->child->email)
                        ->update(['fl_user_id'=>$userRelation->child->id]);
                    
                    $userRelation->child->certificates()
                        ->update(['engineer_id'=>$userRelation->parent->engineers()->where('email',$userRelation->child->email)->orderByDesc('created_at')->first()->id]);
                
                }
            }
        
        
        } elseif($request->input('userRelation.enabled')==0) {
            $userRelation->child->certificates()
                        ->update(['engineer_id'=>null]);
            $userRelation->parent->engineers()->where('fl_user_id',$userRelation->child->id)
                        ->update(['user_id'=>'1']);
        
        }
        
        if($request->input('roleRequest.accepted')==1)
        {
                $roleRequest=UserFlRoleRequest::find($request->input('roleRequest.role_request_id'));
                $roleRequest->accepted='1';
                $roleRequest->save();
                if(!$roleRequest->user->hasRole($request->input('roleRequest.role_name'))) {
                    $roleRequest->user->attachRole($request->input('roleRequest.role_id'));
                }
                
        }elseif($request->input('roleRequest.decline')==1)
        {
                $roleRequest=UserFlRoleRequest::find($request->input('roleRequest.role_request_id'));
                $roleRequest->decline='1';
                $roleRequest->save();
                
        }
        
        
        
        if($request->user()->getAttribute('admin') == 1 || $request->user()->hasRole('ferroli_user') == 1) {
            if($request->input('redirect')=='child') {
                return redirect()->route('admin.users.show',$userRelation->child)->with('success', trans('site::user.relation_updated'));
            }
            else {
                return redirect()->route('admin.users.show',$userRelation->parent)->with('success', trans('site::user.relation_updated'));
            }
        }
        else {
        return redirect()->route('user_relations.index')->with('success', trans('site::user.relation_updated')."<br />" .$updateMessage);
        }
        
    }

   
    public function destroy(UserRelation $userRelation)
    {
        $this->authorize('delete', $userRelation);
        $userRelation->update(['enabled'=> '0']);
        $userRelation->child->certificates()
                        ->update(['engineer_id'=>null]);
        $userRelation->parent->engineers()->where('fl_user_id',$userRelation->child->id)
                        ->update(['user_id'=>null]);
        
        
        if (1) {
            $json['remove'][] = '#relation-' . $userRelation->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);

    }
}