<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ServiceBoiler\Rbac\Repositories\RoleRepository;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Exports\Excel\AddressExcel;
use ServiceBoiler\Prf\Site\Events\AddressApprovedChangeEvent;
use ServiceBoiler\Prf\Site\Filters\Address\AddressPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressShowFerroliBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressShowLamborghiniBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressShowMarketBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressUserSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\CountrySelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\FerroliManagerAddressFilter;
use ServiceBoiler\Prf\Site\Filters\Address\IsApprovedSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\IsEShopSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\IsMounterSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\IsServiceSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\IsShopSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\RegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\Address\TypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\UserFilter;
use ServiceBoiler\Prf\Site\Http\Requests\AddressRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AddressType;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\ProductGroupType;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Repositories\AddressRepository;

class AddressController extends Controller
{
    use AuthorizesRequests;
    
    protected $addresses;

    /**
     * Create a new controller instance.
     *
     * @param AddressRepository $addresses
     */
    public function __construct(
        AddressRepository $addresses,
		RoleRepository $roles)
    {
        $this->addresses = $addresses;
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

        $this->addresses->trackFilter();
		$this->addresses->applyFilter(new FerroliManagerAddressFilter);
        $this->addresses->pushTrackFilter(IsApprovedSelectFilter::class);
        $this->addresses->pushTrackFilter(AddressShowFerroliBoolFilter::class);
        $this->addresses->pushTrackFilter(AddressShowMarketBoolFilter::class);
        $this->addresses->pushTrackFilter(AddressShowLamborghiniBoolFilter::class);
        $this->addresses->pushTrackFilter(CountrySelectFilter::class);
        $this->addresses->pushTrackFilter(TypeSelectFilter::class);
        $this->addresses->pushTrackFilter(IsShopSelectFilter::class);
        $this->addresses->pushTrackFilter(IsServiceSelectFilter::class);
        $this->addresses->pushTrackFilter(IsEShopSelectFilter::class);
        $this->addresses->pushTrackFilter(IsMounterSelectFilter::class);
        $this->addresses->pushTrackFilter(AddressUserSelectFilter::class);
        $this->addresses->pushTrackFilter(UserFilter::class);
        $this->addresses->pushTrackFilter(RegionSelectFilter::class);
        $this->addresses->pushTrackFilter(SearchFilter::class);
        if ($request->has('excel')) {
            (new AddressExcel())->setRepository($this->addresses)->render();
        }
        $this->addresses->pushTrackFilter(AddressPerPageFilter::class);

        return view('site::admin.address.index', [
            'repository' => $this->addresses,
            'addresses'  => $this->addresses->paginate($request->input('filter.per_page', config('site.per_page.address', 10)), ['addresses.*'])
        ]);
    }

    /**
     * @param Address $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        $address_types = AddressType::query()->find([$address->type_id, 2, 5]);
        $countries = Country::query()->where('enabled', 1)->orderBy('sort_order')->get();
        $regions = Region::query()->whereIn('country_id', $countries->toArray())->orderBy('name')->get();
        $product_group_types = ProductGroupType::query()->get();

        return view('site::admin.address.edit', compact('address', 'countries', 'regions', 'address_types', 'product_group_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AddressRequest $request
     * @param  Address $address
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, Address $address)
    {     
            if(!$address->approved & $request->filled('address.approved')){
			event(new AddressApprovedChangeEvent($address));
			}
            $address->update(array_merge(
                $request->input(['address']),
                ['show_ferroli' => $request->filled('address.show_ferroli')],
                ['show_lamborghini' => $request->filled('address.show_lamborghini')],
                ['show_market_ru' => $request->filled('address.show_market_ru')],
                ['approved' => $request->filled('address.approved')],
                ['is_shop' => $request->filled('address.is_shop')],
                ['is_service' => $request->filled('address.is_service')],
                ['is_eshop' => $request->filled('address.is_eshop')],
                ['is_mounter' => $request->filled('address.is_mounter')]
            ));

        $address->product_group_types()->sync($request->input('product_group_types', []));
		
			
		
        return redirect()->route('admin.addresses.show', $address)->with('success', trans('site::address.updated'));
    }

    /**
     * @param Address $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {   
        $this->authorize('view', $address);
        $authorization_roles = AuthorizationRole::query()->get();
		$authorization_types = AuthorizationType::query()->where('enabled', 1)->orderBy('brand_id')->orderBy('name')->get();
        $authorization_accepts = $address->addressable->authorization_accepts;
       //$user_roles = $address->addressable->roles->all();
        $roles = $this->roles->all();
        return view('site::admin.address.show', compact('address','authorization_accepts','authorization_roles','authorization_types','roles'));
    }

    public function destroy(Address $address)
    {
        $address->phones()->delete();
        if ($address->delete()) {
            $json['remove'][] = '#address-' . $address->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);
    }

}
