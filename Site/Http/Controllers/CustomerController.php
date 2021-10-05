<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Customer\CustomerBelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\CustomerRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\CustomerContactRequest;
use ServiceBoiler\Prf\Site\Models\Customer;
use ServiceBoiler\Prf\Site\Models\CustomerRole;
use ServiceBoiler\Prf\Site\Models\CustomerContact;
use ServiceBoiler\Prf\Site\Repositories\CustomerRepository;
use ServiceBoiler\Prf\Site\Repositories\CustomerContactRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;


class CustomerController extends Controller
{

    use AuthorizesRequests;

    protected $customers;

    /**
     * Create a new controller instance.
     *
     * @param CustomerRepository $customers
     */
    public function __construct(
        RegionRepository $regions,
        CustomerRepository $customers,
        CustomerContactRepository $contacts
        )
    {
        $this->regions = $regions;
        $this->customers = $customers;
        $this->contacts = $contacts;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->customers->applyFilter(New CustomerBelongsUserFilter());

        return view('site::customer.index', [
            'repository' => $this->customers,
            'customers' => $this->customers->paginate($request->input('filter.per_page', config('site.per_page.customer', 10)), ['customers.*'])
        ]);
    }

    public function show(Customer $customer, Request $request)
    {
        $view = $request->ajax() ? 'site::customer.show_modal' : 'site::customer.show';
         return view($view, compact('customer'));
    }

	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(CustomerRequest $request)
    {
        //$this->authorize('create', Customer::class);
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $roles = CustomerRole::query()->orderBy('title')->get();
         $view = $request->ajax() ? 'site::customer.form_create' : 'site::customer.create';
         return view($view, compact('roles','regions'));

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $customer = $this->customers->create($request->input(['customer']));
        $customer->manager_id = auth()->user()->id;
        $customer->save();
       
       if(!empty($request->input('roles'))) {
            $customer->attachCustomerRoles($request->input('roles'));
        }
        
        return redirect()->route('customers.show', $customer)->with('success', trans('site::admin.customer.created'));
    }

    public function edit(Customer $customer)
    {
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $roles = CustomerRole::query()->orderBy('title')->get();
        
        return view('site::customer.edit', compact('customer','roles','regions'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->input(['customer']));
        
        if($request->input('roles')) {
        $customer->syncCustomerRoles($request->input('roles'));
        } else {
           $customer->detachCustomerRoles($customer->customerRoles->toArray());     
        }
        
      
        return redirect()->route('customers.show', $customer)->with('success', trans('site::admin.customer.updated'));
    }
    
    public function destroy(Customer $customer)
    {
        
        if ($customer->delete()) {
            Session::flash('success', 'Запись удалена');
        } else {
            Session::flash('error', trans('site::customer.error.deleted'));
        }
        $json['redirect'] = route('customers.index');

        return response()->json($json);
        
        
    }
    
}