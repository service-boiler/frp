<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Rbac\Models\Role;
use ServiceBoiler\Prf\Site\Filters\Address\AddressOnlineStoreFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionDealerMapFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionServiceMapFilter;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Repositories\AddressRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;


class ShopController extends Controller
{
    /**
     * @var RegionRepository
     */
    protected $regions;
    /**
     * @var AddressRepository
     */
    private $addresses;

    private $authorization_types;

    /**
     * Create a new controller instance.
     *
     * @param RegionRepository $regions
     * @param AddressRepository $addresses
     */
    public function __construct(
        RegionRepository $regions,
        AddressRepository $addresses
    )
    {
        $this->regions = $regions;
        $this->addresses = $addresses;
        $this->authorization_types = AuthorizationType::query()
            ->where('brand_id', 1)
            ->where('enabled', 1)
            ->orderBy('name')
            ->get();
    }

    /**
     * Сервисные центры
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function service_centers(Request $request)
    {

        $selected_authorization_types = $request->input('filter.authorization_type', $this->authorization_types->pluck('id')->toArray());
        $regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionServiceMapFilter())
            ->all();
        $region_id = $request->input('filter.region_id');
        $authorization_types = $this->authorization_types;

        return view('site::shop.service_center', compact(
            'regions',
            'region_id',
            'selected_authorization_types',
            'authorization_types'
        ));
    }

    /**
     * Где купить
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function where_to_buy(Request $request)
    {

        $selected_authorization_types = $request->input('filter.authorization_type', $this->authorization_types->pluck('id')->toArray());
        $regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionDealerMapFilter())
            ->all();
        $region_id = $request->input('filter.region_id');
        $authorization_types = $this->authorization_types;

        return view('site::shop.where_to_buy', compact(
            'regions',
            'region_id',
            'selected_authorization_types',
            'authorization_types'
        ));

    }

    /**
     * Интернет-магазины
     *
     * @return \Illuminate\Http\Response
     */
    public function online_stores()
    {

        $addresses = $this->addresses
            ->trackFilter()
            ->applyFilter(new AddressOnlineStoreFilter())
            ->all();
        $roles = Role::query()->where('display', 1)->get();

        return view('site::shop.online_store', compact('addresses', 'roles'));
    }

}
