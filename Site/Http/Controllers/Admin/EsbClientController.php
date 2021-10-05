<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ServiceBoiler\Rbac\Repositories\RoleRepository;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductEnabledFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductUserFilter;
use ServiceBoiler\Prf\Site\Filters\User\HasEngineersRolesFilter;
use ServiceBoiler\Prf\Site\Filters\User\HasEsbClientsRolesFilter;
use ServiceBoiler\Prf\Site\Http\Requests\EngineerRequest;
use ServiceBoiler\Prf\Site\Models\Brand;
use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Models\CertificateType;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\EsbContractTemplate;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\UserType;
use ServiceBoiler\Prf\Site\Repositories\CountryRepository;
use ServiceBoiler\Prf\Site\Repositories\EngineerRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbClientRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbUserProductRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class EsbClientController extends Controller
{

    use AuthorizesRequests, StoreMessages;

    protected $clients;
    protected $countries;
    protected $roles;
    protected $esbUserProducts;

    public function __construct(EsbClientRepository $clients,RoleRepository $roles, EsbUserProductRepository $esbUserProducts)
    {
        $this->clients = $clients;
        $this->roles = $roles;
        $this->esbUserProducts = $esbUserProducts;

    }

    public function index(Request $request)
    {
        $this->clients->trackFilter();
        $this->clients->applyFilter(new HasEsbClientsRolesFilter());
        return view('site::admin.user.client_index', [
            'repository' => $this->clients,
            'clients'  => $this->clients->paginate($request->input('filter.per_page', config('site.per_page.client', 10)), ['users.*'])
        ]);
    }


    public function show(User $esbClient)
    {
        $user=$esbClient;
        $this->authorize('view', $user);
        $addresses = $user->addresses()->get();
        $contacts = $user->contacts()->get();
        $roles = $this->roles->all();
        $roles_fl = $this->roles->all()->where('role_fl','1');
        $commentBox = new CommentBox($user);

        $certificate_types = CertificateType::query()->get();
        return view('site::admin.user.client_show', compact('user','addresses','contacts','roles','roles_fl','commentBox','certificate_types'));
    }
    public function edit(User $client)
    {
        $user=$client;
        $certificate_types = CertificateType::query()->get();
        $roles = $this->roles->all();
        $regions=Region::query()->where('country_id',config('site.country'))->get();
        $userTypes=UserType::query()->get();

        return view('site::admin.user.client_edit', compact('user','roles','certificate_types','regions','userTypes'));
    }

    public function create()
    {

        $certificate_types = CertificateType::query()->get();
        $roles = $this->roles->all();
        $regions=Region::query()->where('country_id',config('site.country'))->get();
        $userTypes=UserType::query()->get();

        return view('site::admin.user.client_create', compact('roles','certificate_types','regions','userTypes'));
    }


    public function store(EngineerRequest $request)
    {
        $data = $request->input('user');


        $user=User::create([

            'region_id' => $data['region_id'],
            'active' => $data['active'],
            'display' => 0,
            'verified' => $data['verified'],
            'type_id' => $data['type_id'],
            'name' => $data['name'],
            'name_for_site' => $data['name_for_site'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
        if(!empty($request->input('roles'))) {
            $user->syncRoles($request->input('roles'));
        }

        if(in_array($data['type_id'],config('site.types_company')) && $request->input('contragent.inn'))
        {
            $contragent=$user->contragents()->create(array_merge($request->input('contragent'),['name'=>$data['name']]));
            $address_legal=$contragent->addresses()->create(array_merge($request->input('legal_address'),['type_id'=>'1','country_id'=>config('site.country')]));

        }
        if($request->input('address.full')){
            $address=$user->addresses()->create(array_merge($request->input('address'),['type_id'=>'2','country_id'=>config('site.country')]));
        }


        if($request->input('product.name')){
            $esbProduct=$user->esbProducts()->create([
                'product_id'=>$request->input('product.id'),
                'address_id' => $address ? $address->id : null,
                'product_no_cat' => $request->input('product.id') ? $request->input('product.comment') .' created by' .auth()->user()->name : $request->input('product.name') . ' ' .$request->input('product.comment') .' created by' .auth()->user()->name,
            ]);
        }

        return redirect()->route('admin.esb-clients.show',$user);
    }
    public function update(EngineerRequest $request, User $client)
    {
        $client->update($request->input('user'));

        if(!empty($request->input('roles'))) {
            $client->syncRoles($request->input('roles'));
        }

        return redirect()->route('admin.esb-clients.show', $client)->with('success', trans('site::client.updated'));
    }

    public function esbProducts(Request $request, User $esbClient)
    {
        $this->esbUserProducts->trackFilter();
        $this->esbUserProducts->applyFilter(new EsbUserProductEnabledFilter());
        $this->esbUserProducts->applyFilter((new EsbUserProductUserFilter())->setUser($esbClient));
        return view('site::admin.user.client_products_index',[
            'repository' => $this->esbUserProducts,
            'esbUserProducts'    => $this->esbUserProducts->paginate($request->input('filter.per_page', 100), ['esb_user_products.*']),
            'user'=>$esbClient
        ]);
    }

    public function createContract(Request $request, User $esbClient)
    {
        $contragents = auth()->user()->company()->contragents;
        $templates=EsbContractTemplate::query()->where('enabled',1)->where(function ($q){$q->where('shared',1)->orWhere('user_id',auth()->user()->company()->id);})->get();
        $date = Carbon::now();
        $date_to = Carbon::now()->addYear();
        $max_id = DB::table('contracts')->max('id');

        return view('site::esb_contract.create', compact('templates','date','date_to','max_id','contragents','esbClient'));

    }

    public function createProduct(Request $request, User $esbClient)
    {
        $user=$esbClient;
        $addresses=$user->addresses;

        $equipments = collect([]);
        $products = collect([]);

        $equipments_archive = Equipment::query()
            ->where('enabled', 1)
            ->whereHas('products', function ($product) {
                $product
                    ->where('enabled', 1);
            })
            ->whereHas('catalog', function ($catalog) {
                $catalog
                    ->where('enabled', 1);
            })
            ->orderBy('name')
            ->get();
        $products = collect([]);

        if(old('equipment_id')){
            $products = Product::query()
                ->where('equipment_id', old('equipment_id'))
                ->mounter()
                ->get();
        }
        if(old('brand_id')){
            $equipments = Equipment::query()
                ->where(config('site.check_field'), 1)
                ->where('enabled', 1)
                ->where('catalog_id','!=','52')
                ->whereHas('products', function ($product) {
                    $product
                        ->where(config('site.check_field'), 1)
                        ->where('enabled', 1);
                })
                ->whereHas('catalog', function ($catalog) {
                    $catalog
                        ->where(config('site.check_field'), 1)
                        ->where('enabled', 1);
                })
                ->orderBy('name')
                ->get();
        }
        $brands=Brand::query()->whereEnabled('1')->get();
        return view('site::admin.user.client_products_create', compact('equipments','products', 'user','addresses','brands'));

    }

}