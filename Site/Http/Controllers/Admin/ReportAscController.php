<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Rbac\Repositories\RoleRepository;
use ServiceBoiler\Prf\Site\Events\UserScheduleEvent;
use ServiceBoiler\Prf\Site\Exports\Excel\UserExcel;
use ServiceBoiler\Prf\Site\Filters\District\SortFilter;
use ServiceBoiler\Prf\Site\Filters\User\ActiveSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\AddressSearchFilter;
use ServiceBoiler\Prf\Site\Filters\User\ContactSearchFilter;
use ServiceBoiler\Prf\Site\Filters\User\ContragentSearchFilter;
use ServiceBoiler\Prf\Site\Filters\User\DisplaySelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsAscSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsCscSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsDealerSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsDistrSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsEshopSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsGendistrSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsMontageSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserSortFilter;
use ServiceBoiler\Prf\Site\Filters\User\VerifiedFilter;
use ServiceBoiler\Prf\Site\Filters\UserSearchFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\UserRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Models\Contact;
use ServiceBoiler\Prf\Site\Models\ContragentType;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Phone;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\DistrictRepository;
use ServiceBoiler\Prf\Site\Repositories\TemplateRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use ServiceBoiler\Prf\Site\Repositories\WarehouseRepository;

class ReportAscController extends Controller
{

    use AuthorizesRequests;
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
        $this->users->pushTrackFilter(UserSearchFilter::class);
        $this->users->pushTrackFilter(ContactSearchFilter::class);
        $this->users->pushTrackFilter(ContragentSearchFilter::class);
        $this->users->pushTrackFilter(RegionFilter::class);
        $this->users->pushTrackFilter(AddressSearchFilter::class);
        $this->users->pushTrackFilter(IsMontageSelectFilter::class);
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
        if ($request->has('excel')) {
            (new UserExcel())->setRepository($this->users)->render();
        }

        return view('site::admin.reports.asc.index', [
            'roles'      => $this->roles->all(),
            'repository' => $this->users,
            'users'      => $this->users->paginate(config('site.per_page.user', 10), ['users.*'])
        ]);
    }

    

}
