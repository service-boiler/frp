<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsDealerFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressMapFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsServiceFilter;
use ServiceBoiler\Prf\Site\Filters\Address\RegionFilter;
use ServiceBoiler\Prf\Site\Http\Resources\Address\DealerCollection;
use ServiceBoiler\Prf\Site\Http\Resources\Address\ServiceCollection;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Repositories\AddressRepository;

class ShopController extends Controller
{

    private $addresses;

    private $authorization_types;

    /**
     * Create a new controller instance.
     *
     * @param AddressRepository $addresses
     */
    public function __construct(AddressRepository $addresses)
    {
        $this->addresses = $addresses;
        $this->authorization_types = AuthorizationType::query()
            ->where('brand_id', 1)
            ->where('enabled', 1)
            ->orderBy('name')
            ->get();
    }

    /**
     * @param Request $request
     * @param Region $region
     * @return ServiceCollection
     */
    public function service_centers(Request $request, Region $region)
    {

        return new ServiceCollection(
            $this->addresses
                ->trackFilter()
                ->applyFilter(new AddressIsServiceFilter())
                ->applyFilter(
                    (new AddressMapFilter())
                        ->setAccepts($request->input(
                            'filter.authorization_type',
                            $this->authorization_types->pluck('id')->toArray()
                        ))
                        ->setRoleId(3)
                )
                ->applyFilter((new RegionFilter())->setRegionId($region->id))
                ->all()
        );
    }

    /**
     * @param Request $request
     * @param Region $region
     * @return DealerCollection
     */
    public function where_to_buy(Request $request, Region $region)
    {

        return new DealerCollection(
            $this->addresses
                ->trackFilter()
                ->applyFilter(new AddressIsDealerFilter())
                ->applyFilter(
                   (new AddressMapFilter())
                       ->setAccepts($request->input(
                           'filter.authorization_type',
                          $this->authorization_types->pluck('id')->toArray()
                        ))
                        ->setRoleId(4)
                )
                ->applyFilter((new RegionFilter())->setRegionId($region->id))
                ->all()
        );
    }

//    public function location()
//    {
//        $ip = request()->ip();
//
//        //$ip = '77.246.239.18';
//        return new LocationResource(Location::get($ip));
//    }
}
