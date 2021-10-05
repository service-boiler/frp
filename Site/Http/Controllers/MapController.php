<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Rbac\Models\Role;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Address\AddressOnlineStoreFilter;
use ServiceBoiler\Prf\Site\Filters\Address\ActiveFilter;
use ServiceBoiler\Prf\Site\Filters\Order\AddressRegionWarehouseSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionBlackListMapFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionDealerMapFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionMounterMapFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionOnlineStoreMapFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionServiceMapFilter;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\AddressRepository;
use ServiceBoiler\Prf\Site\Repositories\BlackListRepository;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;

use ServiceBoiler\Prf\Site\Http\Resources\Address\DealerCollection;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsApprovedFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsDealerFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsServiceFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsMounterFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressMapFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressMapContractFilter;
use ServiceBoiler\Prf\Site\Filters\Address\RegionFilter;

use ServiceBoiler\Prf\Site\Filters\User\IsAscFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsDealerFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsMontageFilter;
use ServiceBoiler\Prf\Site\Filters\User\DisplayFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserIsNotFlFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserHasAddressRegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserHasAddressRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserMapFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserMapContractFilter;

class MapController extends Controller
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
        UserRepository $users,
        AddressRepository $addresses,
        BlackListRepository $blackList,
        HeadBannerBlockRepository $headBannerBlocks
    )
    {
        $this->users = $users;
        $this->regions = $regions;
        $this->addresses = $addresses;
        $this->headBannerBlocks = $headBannerBlocks;
        $this->blackList = $blackList;
        $this->authorization_types = AuthorizationType::query()
            ->where('brand_id', config('site.brand_default'))
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
    {   $selected_authorization_types = $request->input('filter.authorization_type', $this->authorization_types->pluck('id')->toArray());
        $regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionServiceMapFilter())
            ->all();
        $region_id = $request->input('filter.region_id');
        $authorization_types = $this->authorization_types;
        
        $services = $this->users
                    ->applyFilter(new IsAscFilter())
                    ->applyFilter(new DisplayFilter())
                    ->applyFilter(new UserIsNotFlFilter())
                    ->applyFilter(new UserMapContractFilter())
                    ->applyFilter((new UserHasAddressRoleFilter())->setRoleName('is_service'))
                    ->applyFilter((new UserHasAddressRoleFilter())->setRoleName('show_ferroli'))
                    ->applyFilter((new UserMapFilter())
                                        ->setAccepts($request->input('filter.authorization_type',$this->authorization_types->pluck('id')->toArray()))
                                        ->setRoleId(3)
                                        ->setRegionId($request->input('filter.region_id'))
                                        )
                    ->all();
        
        $addresses = $this->addresses
                ->trackFilter()
                ->applyFilter(new AddressIsApprovedFilter())
                ->applyFilter(new AddressIsServiceFilter())
                ->applyFilter(new AddressMapContractFilter())
                ->applyFilter(
                    (new AddressMapFilter())
                        ->setAccepts($request->input(
                            'filter.authorization_type',
                            $this->authorization_types->pluck('id')->toArray()
                        ))
                        ->setRoleId(3)
                )
                ->applyFilter((new RegionFilter())->setRegionId($request->input('filter.region_id')))
                ->all();
        

        return view('site::map.service_center', compact(
            'regions',
            'region_id',
            'selected_authorization_types',
            'authorization_types',
            'addresses',
            'services'
        ));
    }

    /**
     * Где купить
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function where_to_buy(Request $request, Region $region)
    {

        $selected_authorization_types = $request->input('filter.authorization_type', $this->authorization_types->pluck('id')->toArray());
        $regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionDealerMapFilter())
            ->all();
        $region_id = $request->input('filter.region_id');
        $authorization_types = $this->authorization_types;

        
        $dealers = $this->users
                    ->applyFilter(new IsDealerFilter())
                    ->applyFilter(new DisplayFilter())
                    ->applyFilter(new UserIsNotFlFilter())
                    ->applyFilter((new UserHasAddressRoleFilter())->setRoleName('is_shop'))
                    //->applyFilter((new UserHasAddressRoleFilter())->setRoleName('show_ferroli'))
                    ->applyFilter((new UserMapFilter())
                                        ->setAccepts($request->input('filter.authorization_type',$this->authorization_types->pluck('id')->toArray()))
                                        ->setRoleId(4)
                                        ->setRegionId($request->input('filter.region_id'))
                                        )
                    ->all();
        
        $addresses = $this->addresses
                ->trackFilter()
                ->applyFilter(new AddressIsApprovedFilter())
                ->applyFilter(new AddressIsDealerFilter())
                ->applyFilter(
                    (new AddressMapFilter())
                        ->setAccepts($request->input(
                            'filter.authorization_type',
                            $this->authorization_types->pluck('id')->toArray()
                        ))
                        ->setRoleId(4)
                )
                ->applyFilter((new RegionFilter())->setRegionId($request->input('filter.region_id')))
                ->all();
         
               
        return view('site::map.where_to_buy', compact(
            'regions',
            'region_id',
            'selected_authorization_types',
            'authorization_types',
            'addresses',
            'dealers'
        ));

    }

    /**
     * Заявки на монтаж
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function mounter_requests(Request $request)
    {

        $selected_authorization_types = $request->input('filter.authorization_type', $this->authorization_types->pluck('id')->toArray());
        $regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionMounterMapFilter())
            ->all();
        $region_id = $request->input('filter.region_id');
        $authorization_types = $this->authorization_types;
        
         $mounters = $this->users
                    ->applyFilter(new IsMontageFilter())
                    ->applyFilter(new DisplayFilter())
                    ->applyFilter(new UserIsNotFlFilter())
                    ->applyFilter((new UserHasAddressRoleFilter())->setRoleName('is_mounter'))
                    ->applyFilter((new UserHasAddressRoleFilter())->setRoleName('show_ferroli'))
                    ->applyFilter((new UserMapFilter())
                                        ->setAccepts($request->input('filter.authorization_type',$this->authorization_types->pluck('id')->toArray()))
                                        ->setRoleId(11)
                                        ->setRegionId($request->input('filter.region_id'))
                                        )
                    ->all();
        
        $addresses = $this->addresses
                ->trackFilter()
                ->applyFilter(new AddressIsApprovedFilter())
                ->applyFilter(new AddressIsMounterFilter())
                ->applyFilter(
                    (new AddressMapFilter())
                        ->setAccepts($request->input(
                            'filter.authorization_type',
                            $this->authorization_types->pluck('id')->toArray()
                        ))
                        ->setRoleId(11)
                )
                ->applyFilter((new RegionFilter())->setRegionId($request->input('filter.region_id')))
                ->all();

        return view('site::map.mounter_request', compact(
            'regions',
            'region_id',
            'selected_authorization_types',
            'authorization_types',
            'addresses',
            'mounters'
        ));

    }

    /**
     * Интернет-магазины
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function online_stores(Request $request)
    {
        $regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionOnlineStoreMapFilter())
            ->all();
        $region_id = $request->input('filter.region_id');

        $addresses = $this->addresses
            ->trackFilter()
            ->applyFilter(new AddressOnlineStoreFilter())
            ->pushTrackFilter(AddressRegionWarehouseSelectFilter::class)
            ->all();
        $roles = Role::query()->where('display', 1)->get();
        $repository = $this->addresses;

        return view('site::map.online_store', compact('addresses', 'roles', 'regions', 'region_id', 'repository'));
    }
    
    public function black_list(Request $request)
    {
        $regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionBlackListMapFilter())
            ->all();
        $region_id = $request->input('filter.region_id');
		
        $headBannerBlocks = $this->headBannerBlocks
            ->applyFilter(new Filters\ShowFilter())
            ->applyFilter(new Filters\BySortOrderFilter())
            ->applyFilter(new Filters\PathUrlFilter())
            ->all(['head_banner_blocks.*']);
            
        $addresses = $this->blackList
            ->trackFilter()
            ->applyFilter(new ActiveFilter())
            ->all();
        $repository = $this->blackList;

        return view('site::map.black_list', compact('addresses',  'regions', 'region_id', 'repository','headBannerBlocks'));
    }

    
    public function publicUserCard(User $user)
    {
        return view('site::map.public_user_card', compact('user'));
    }

}
