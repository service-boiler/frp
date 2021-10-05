<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Rbac\Repositories\RoleRepository;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\UserScheduleEvent;
use ServiceBoiler\Prf\Site\Mail\User\UserInviteEmail;
use ServiceBoiler\Prf\Site\Exports\Excel\UserExcel;
use ServiceBoiler\Prf\Site\Filters\District\SortFilter;
use ServiceBoiler\Prf\Site\Filters\FerroliManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\User\ActiveSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\AddressSearchFilter;
use ServiceBoiler\Prf\Site\Filters\User\ContactSearchFilter;
use ServiceBoiler\Prf\Site\Filters\User\ContragentSearchFilter;
use ServiceBoiler\Prf\Site\Filters\User\DisplaySelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\HasMountingSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\HasPreregSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsAscSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsCscSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsDealerSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsDistrSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsEshopSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsGendistrSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsMontageSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsMontageFlSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserChiefsFerroliFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserSortFilter;
use ServiceBoiler\Prf\Site\Filters\User\VerifiedFilter;
use ServiceBoiler\Prf\Site\Filters\UserSearchFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\UserRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Models\AcademyProgram;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Models\Contact;
use ServiceBoiler\Prf\Site\Models\ContragentType;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Phone;
use ServiceBoiler\Prf\Site\Models\ProductGroupType;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\DistrictRepository;
use ServiceBoiler\Prf\Site\Repositories\TemplateRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use ServiceBoiler\Prf\Site\Repositories\WarehouseRepository;
use ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class UserController extends Controller
{

	use AuthorizesRequests, StoreMessages;
	/**
	 * @var UserRepository
	 */
	protected $users;

	/**
	 * @var RoleRepository
	 */
	protected $roles;

	/**
	 * @var WarehouseRepository
	 */
	protected $warehouses;

	/**
	 * @var TemplateRepository
	 */
	private $templates;
	/**
	 * @var DistrictRepository
	 */
	private $districts;


	/**
	 * Create a new controller instance.
	 *
	 * @param UserRepository $users
	 * @param RoleRepository $roles
	 * @param WarehouseRepository $warehouses
	 * @param TemplateRepository $templates
	 * @param DistrictRepository $districts
	 */
	public function __construct(
		UserRepository $users,
		RoleRepository $roles,
		WarehouseRepository $warehouses,
		TemplateRepository $templates,
		DistrictRepository $districts
	)
	{
		$this->users = $users;
		$this->roles = $roles;
		$this->warehouses = $warehouses;
		$this->templates = $templates;
		$this->districts = $districts;
	}

	/**
	 * Show the user profile
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	
    public function index(Request $request)
	{
		$this->users->trackFilter();
		$this->users->applyFilter(new UserNotAdminFilter);
		//$this->users->applyFilter(new FerroliManagerAttachFilter);
		$this->users->pushTrackFilter(UserSearchFilter::class);
		$this->users->pushTrackFilter(ContactSearchFilter::class);
		$this->users->pushTrackFilter(ContragentSearchFilter::class);
		$this->users->pushTrackFilter(RegionFilter::class);
		$this->users->pushTrackFilter(AddressSearchFilter::class);
		$this->users->pushTrackFilter(IsMontageSelectFilter::class);
		$this->users->pushTrackFilter(IsMontageFlSelectFilter::class);
		$this->users->pushTrackFilter(IsAscSelectFilter::class);
		$this->users->pushTrackFilter(IsCscSelectFilter::class);
		$this->users->pushTrackFilter(IsDealerSelectFilter::class);
		$this->users->pushTrackFilter(IsDistrSelectFilter::class);
		$this->users->pushTrackFilter(IsGendistrSelectFilter::class);
		$this->users->pushTrackFilter(IsEshopSelectFilter::class);
		$this->users->pushTrackFilter(ActiveSelectFilter::class);
		$this->users->pushTrackFilter(VerifiedFilter::class);
		$this->users->pushTrackFilter(DisplaySelectFilter::class);
		$this->users->pushTrackFilter(UserSortFilter::class);
		$this->users->pushTrackFilter(UserRoleFilter::class);
		$this->users->pushTrackFilter(HasMountingSelectFilter::class); 
		$this->users->pushTrackFilter(HasPreregSelectFilter::class); 
		if ($request->has('excel')) {
			(new UserExcel())->setRepository($this->users)->render();
		}


		return view('site::admin.user.index', [
			'roles' => $this->roles->all(),
            'repository' => $this->users,
			'users' => $this->users->paginate(config('site.per_page.user', 10), ['users.*']),
		]);
	}

	public function create()
	{
		$countries = Country::query()->where('enabled', 1)->orderBy('sort_order')->get();
		$regions = Region::query()->whereHas('country', function ($country) {
			$country->where('enabled', 1);
		})->orderBy('name')->get();
		$types = ContragentType::all();
        if(Auth()->user()->admin == 1){
        $roles = $this->roles->all();
        } else {
        $roles = $this->roles->all()->where('for_create_by_manager','1');
        }
        $product_group_types = ProductGroupType::query()->get();
		return view('site::admin.user.create', compact('countries', 'regions', 'types', 'roles','product_group_types'));
	}

	public function store(UserRequest $request)
	{
       
		$user = $this->createUser($request->all());
		/** @var $sc Contact */
		if ($request->filled('sc')) {
			/** @var $sc Contact */
			$user->contacts()->save($sc = Contact::create($request->input('sc')));
			$sc->phones()->save(Phone::create($request->input('phone.sc')));
		}
		$address = $user->addresses()->save($address = Address::create(
        
        array_merge($request->input('address.sc'),
            ['show_ferroli' => $request->filled('address.sc.show_ferroli')],
            ['show_lamborghini' => $request->filled('address.sc.show_lamborghini')],
            ['show_market_ru' => $request->filled('address.sc.show_market_ru')],
            ['approved' => $request->filled('address.sc.approved')],
            ['is_shop' => $request->filled('address.sc.is_shop')],
            ['is_service' => $request->filled('address.sc.is_service')],
            ['is_eshop' => $request->filled('address.sc.is_eshop')],
            ['is_mounter' => $request->filled('address.sc.is_mounter')]
        
        )
        
        ));
       
		//$user->attachRole(4);
        if($request->input('invite_user')){
         Mail::to($user->email)->send(new UserInviteEmail($user));
        }
        
		return redirect()->route('admin.users.show', $user)->with('success', trans('site::user.created'));
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 *
	 * @return User
	 */
	protected function createUser(array $data)
	{
		$user=User::create([

			'region_id' => $data['address']['sc']['region_id'],
			'dealer' => $data['dealer'],
			'active' => $data['active'],
			'display' => $data['display'],
			'verified' => $data['verified'],
			'name' => $data['name'],
			'name_for_site' => $data['name_for_site'],
			'email' => $data['email'],
		]);
        if(!empty($data['roles'])) {
        $user->syncRoles($data['roles']);
        }
        return $user;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param User $user
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{   
        $this->authorize('view', $user);
        $addresses = $user->addresses()->get();
		$contacts = $user->contacts()->get();
		$roles = $this->roles->all();
        $roles_fl = $this->roles->all()->where('role_fl','1');
		$authorization_accepts = $user->authorization_accepts()->get();
		$authorization_roles = AuthorizationRole::query()->get();
		$authorization_types = AuthorizationType::query()->where('enabled', 1)->orderBy('brand_id')->orderBy('name')->get();

		$commentBox = new CommentBox($user);

		return view('site::admin.user.show', compact(
			'user',
			'addresses',
			'contacts',
			'roles','roles_fl',
			'authorization_types',
			'authorization_roles',
			'authorization_accepts',
			'commentBox'
		));
	}

	public function digift(User $user)
	{

		if ($user->digiftUser()->doesntExist()) {
			$user->makeDigiftUser();
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  User $user
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(User $user)
	{
		if(auth()->user()->hasRole('админ') || auth()->user()->hasRole('supervisor')) {
            $roles = $this->roles->all();
        } else {
                $roles = $this->roles->all()->where('for_create_by_manager','1');
        }
		
		$warehouses = $this->warehouses->all();
		$this->districts->applyFilter(new SortFilter());
		$districts = $this->districts->all();
        $this->users->applyFilter(new UserChiefsFerroliFilter);
        $chiefs = $this->users->all();
        
		return view('site::admin.user.edit', compact('user', 'roles', 'warehouses', 'districts','chiefs'));
	}


	/**
	 * @param User $user
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function schedule(User $user)
	{
		$this->authorize('schedule', $user);
		event(new UserScheduleEvent($user));

		return redirect()->route('admin.users.show', $user)->with('success', trans('site::schedule.created'));

	}

	/**
	 * Обновить сервисный центр
	 *
	 * @param  UserRequest $request
	 * @param  User $user
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserRequest $request, User $user)
	{   
        $data = $request->input('user');

		if ($request->input('user.verified') == 1) {
			$data = array_merge($data, ['verify_token' => null]);
		}
		$user->update($data);
		$user->syncRoles($request->input('roles', []));
        
        DB::table('user_subordinates') ->updateOrInsert(
                        ['subordinate_id' => $user->id],
                        [
                            'user_id'         => $request->input('user.chief'),
                            'updated_at'   => Carbon::now(),
                            
                        ]
                    );
		return redirect()->route('admin.users.show', $user)->with('success', trans('site::user.updated'));
	}
    
	public function invite(Request $request, User $user)
	{   
         if($request->input('invite_user')){
         Mail::to($user->email)->send(new UserInviteEmail($user));
         Mail::to(env('MAIL_DEVEL_ADDRESS'))->send(new UserInviteEmail($user));
        }
        
		return redirect()->route('admin.users.show', $user)->with('success', trans('site::user.updated'));
	}
    public function role(Request $request, User $user)
	{    
       
        if($request->input('role_accept') == '1') {
            $user->attachRole($request->input('role_id'));
            $user->UserFlRoleRequests()->where('id',$request->input('role_request_id'))->update(['accepted' => '1']);
        }
        if($request->input('role_decline') == '1') {
        
           $user->UserFlRoleRequests()->where('id',$request->input('role_request_id'))->update(['decline' => '1']);
        }
        
		return redirect()->route('admin.users.show', $user)->with('success', trans('site::user.updated'));
	}

	/**
	 * Логин под пользователем
	 *
	 * @param User $user
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function force(User $user, Request $request)
	{

		Auth::guard()->logout();

		$request->session()->invalidate();

		Auth::login($user);

		return redirect()->route('home');

	}

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Models\User $user
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function message(MessageRequest $request, User $user)
	{
		return $this->storeMessage($request, $user);
	}
	
    public function messages( User $user)
	{   $only_send = 1;
		return view('site::admin.user.message.index', compact('user','only_send'));
	}

}
