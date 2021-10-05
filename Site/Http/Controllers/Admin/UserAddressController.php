<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;


use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\AddressableFilter;
use ServiceBoiler\Prf\Site\Http\Requests\AddressRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AddressType;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\AddressRepository;

class UserAddressController extends Controller
{
    /**
     * @var AddressRepository
     */
    protected $addresses;


    /**
     * Create a new controller instance.
     *
     * @param AddressRepository $addresses
     */
    public function __construct(AddressRepository $addresses)
    {
        $this->addresses = $addresses;
    }

    /**
     * Показать список адресов сервисного центра
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $this->addresses->trackFilter();
        $this->addresses->applyFilter((new AddressableFilter())->setId($user->getKey())->setMorph($user->path()));

        return view('site::admin.user.address.index', [
            'user'       => $user,
            'repository' => $this->addresses,
            'addresses'  => $this->addresses->paginate(config('site.per_page.address', 10), ['addresses.*'])
        ]);
    }

    /**
     * @param User $user
     * Для адресов пользователя доступны фактический, интернет-магазин, оптовый склад 2,5,6
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        $address_types = AddressType::query()->find([2, 5, 6]);
        $countries = Country::query()->where('enabled', 1)->orderBy('sort_order')->get();
        $regions = Region::query()->whereHas('country', function ($country) {
            $country->where('enabled, 1');
        })->orderBy('name')->get();


        return view('site::admin.user.address.create', compact('user', 'countries', 'regions', 'address_types'));
    }

    /**
     * @param AddressRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AddressRequest $request, User $user)
    {
        /** @var $address Address */
        $user->addresses()->save($address = Address::create($request->input('address')));
        $address->phones()->create($request->input('phone'));

        return redirect()->route('admin.users.addresses.index', $user)->with('success', trans('site::address.created'));
    }

}
