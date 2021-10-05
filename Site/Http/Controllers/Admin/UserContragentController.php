<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;


use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\AddressableFilter;
use ServiceBoiler\Prf\Site\Filters\Contragent\ContragentUserFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ContragentRequest;
use ServiceBoiler\Prf\Site\Http\Requests\AddressRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AddressType;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\ContragentType;
use ServiceBoiler\Prf\Site\Repositories\ContragentRepository;
use ServiceBoiler\Prf\Site\Repositories\ContragentTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\OrganizationRepository;

class UserContragentController extends Controller
{
    /**
     * @var ContragentRepository
     */
    protected $contragents;


    /**
     * Create a new controller instance.
     *
     * @param ContragentRepository $contragents
     */
    public function __construct(ContragentRepository $contragents)
    {
        $this->contragents = $contragents;
    }

    /**
     * Показать список адресов сервисного центра
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $this->contragents->trackFilter();
        $this->contragents->applyFilter((new ContragentUserFilter())->setUserId($user->id));

        return view('site::admin.user.contragent.index', [
            'user'       => $user,
            'repository' => $this->contragents,
            'contragents'  => $this->contragents->paginate(config('site.per_page.address', 100), ['contragents.*'])
        ]);
    }

    /**
     * @param User $user
     * Для адресов пользователя доступны фактический, интернет-магазин, оптовый склад 2,5,6
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        
        $countries = Country::query()->where('enabled', 1)->orderBy('sort_order')->get();
        $regions = Region::query()->whereHas('country', function ($query) {
            $query->where('enabled', 1);
        })->orderBy('name')->get();

        $types = ContragentType::all();

        return view('site::admin.user.contragent.create', compact(
            'countries','types','regions','user'
        ));

    }

    /**
     * @param AddressRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContragentRequest $request, User $user)
    {
        $user->contragents()->save($contragent = Contragent::query()->create($request->input('contragent')));
        $contragent->addresses()->saveMany([
            Address::query()->create($request->input('address.legal')),
            Address::query()->create($request->input('address.postal')),
        ]);
        
        return redirect()->route('admin.users.contragents.index', $user)->with('success', trans('site::address.created'));
    }

}
