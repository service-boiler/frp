<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ServiceBoiler\Rbac\Repositories\RoleRepository;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters\User\HasEngineersRolesFilter;
use ServiceBoiler\Prf\Site\Http\Requests\EngineerRequest;
use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Models\CertificateType;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\UserType;
use ServiceBoiler\Prf\Site\Repositories\CountryRepository;
use ServiceBoiler\Prf\Site\Repositories\EngineerRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class EngineerController extends Controller
{

    use AuthorizesRequests, StoreMessages;

    protected $engineers;
    protected $countries;
    protected $roles;

    /**
     * Create a new controller instance.
     *
     * @param EngineerRepository $engineers
     * @param CountryRepository $countries
     */
    public function __construct(EngineerRepository $engineers,RoleRepository $roles)
    {
        $this->engineers = $engineers;
        $this->roles = $roles;

    }

    /**
     * Show the user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->engineers->trackFilter();
        $this->engineers->applyFilter(new HasEngineersRolesFilter());
        return view('site::admin.user.engineer_index', [
            'repository' => $this->engineers,
            'engineers'  => $this->engineers->paginate($request->input('filter.per_page', config('site.per_page.engineer', 10)), ['users.*'])
        ]);
    }


    public function show(User $engineer)
    {
        $user=$engineer;
        $this->authorize('view', $user);
        $addresses = $user->addresses()->get();
        $contacts = $user->contacts()->get();
        $roles = $this->roles->all();
        $roles_fl = $this->roles->all()->where('role_fl','1');
        $commentBox = new CommentBox($user);

        $certificate_types = CertificateType::query()->get();
        return view('site::admin.user.engineer_show', compact('user','addresses','contacts','roles','roles_fl','commentBox','certificate_types'));
    }
    public function edit(User $engineer)
    {
        $user=$engineer;
        $certificate_types = CertificateType::query()->get();
        $roles = $this->roles->all();
        $regions=Region::query()->where('country_id',config('site.country'))->get();
        $userTypes=UserType::query()->get();

        return view('site::admin.user.engineer_edit', compact('user','roles','certificate_types','regions','userTypes'));
    }

    public function create()
    {

        $certificate_types = CertificateType::query()->get();
        $roles = $this->roles->all();
        $regions=Region::query()->where('country_id',config('site.country'))->get();
        $userTypes=UserType::query()->get();

        return view('site::admin.user.engineer_create', compact('roles','certificate_types','regions','userTypes'));
    }


    public function store(EngineerRequest $request)
    {
        $data = $request->input('user');


        $user=User::create([

            'region_id' => $data['region_id'],
            'active' => $data['active'],
            'display' => $data['display'],
            'verified' => $data['verified'],
            'name' => $data['name'],
            'name_for_site' => $data['name_for_site'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
        if(!empty($request->input('roles'))) {
            $user->syncRoles($request->input('roles'));
        }

        return redirect()->route('admin.engineers.show',$user);
    }
    public function update(EngineerRequest $request, User $engineer)
    {
        $engineer->update($request->input('user'));

        if(!empty($request->input('roles'))) {
            $engineer->syncRoles($request->input('roles'));
        }

        return redirect()->route('admin.engineers.show', $engineer)->with('success', trans('site::engineer.updated'));
    }

}