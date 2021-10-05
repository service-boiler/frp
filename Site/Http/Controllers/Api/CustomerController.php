<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\Customer\RegisterCustomerSearchFilter;
//use ServiceBoiler\Prf\Site\Filters\Customer\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\CustomerSearchFilter;
use ServiceBoiler\Prf\Site\Http\Resources\CustomerResource;
use ServiceBoiler\Prf\Site\Http\Resources\CustomerCollection;
use ServiceBoiler\Prf\Site\Http\Resources\CustomerSearchCollection;
use ServiceBoiler\Prf\Site\Models\Customer;
use ServiceBoiler\Prf\Site\Models\CustomerRole;
use ServiceBoiler\Prf\Site\Models\CustomerContact;
use ServiceBoiler\Prf\Site\Repositories\CustomerRepository;

class CustomerController extends Controller
{

    protected $customers;
    

    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $products
     */
    public function __construct(CustomerRepository $customers)
    {
        $this->customers = $customers;
    }

    

    /**
     * @param Customer $user
     * @return CustomerResource
     */
    public function show(Customer $user)
    {
        return new CustomerResource($user);
    }
    
    public function search(Request $request)
    {   
        
        $this->customers->applyFilter(new CustomerSearchFilter());
        $customers = new CustomerSearchCollection($this->customers->all());
       
        return $customers;    
        
    }
    public function create($field_name)
    {   
        if(CustomerRole::where('name',$field_name)->exists()) {
            $title=CustomerRole::where('name',$field_name)->first()->title;
        } else {
        $title='';
        }
        $row_num=rand(30000,50000);
        return view('site::admin.tender.customer_second', compact('field_name','title','row_num'));
        
    }
    public function store(Request $request)
    {   
       $customer = $request->input('customer');
       $contact = $request->input('contact');
       $role_id = CustomerRole::where('name',$customer['role_name'])->first()->id;
    
    
        if(!empty($customer['id'])) {
                        $tmp_customer = Customer::findOrFail($customer['id']);
                        if(!empty($tmp_customer)){
                            $tmp_customer->update($customer) ;
                            if(empty($tmp_customer->customerRoles->find($role_id))){
                                $tmp_customer->attachCustomerRole($role_id);
                            }
                        }
                        
                        
                    //dd($request->input());
                    } else {
                        if(!empty($customer['name'])) {
                            $tmp_customer = Customer::where('name',$customer['name'])->orderByDesc('created_at')->first();
                            if(!empty($tmp_customer)) {
                                $tmp_customer->update($customer);
                            } else {
                                $tmp_customer = Customer::create($customer);
                                if(empty($tmp_customer->customerRoles->find($role_id))){
                                    $tmp_customer->attachCustomerRole($role_id);
                                }
                            }
                                
                        }
                    }
                    
                    if(!empty($tmp_customer)) {
                        if(!empty($contact['id'])) {
                            $tmp_contact = CustomerContact::findOrFail($contact['id']);
                            
                            if(!empty($tmp_contact)){
                                    $tmp_contact->update($contact) ;
                            } 
                        } else {
                            if(!empty($contact['name'])) {
                                $tmp_contact = CustomerContact::where('name',$contact['name'])->where('customer_id',$tmp_customer->id)->orderByDesc('created_at')->first();
                                $contact['customer_id']=$tmp_customer->id;
                                $contact['lpr']=='on' ? $contact['lpr']=1 :'';
                                if(empty($tmp_contact)) {
                                    $tmp_contact = CustomerContact::create($contact);
                                } else {
                                    $tmp_contact->update($contact);
                                }
                               
                            }
                            
                        }
                    }
        if(!empty($tmp_contact) && !empty($tmp_customer)) {
            return ['customer'=>$tmp_customer->toArray(),'contact'=>$tmp_contact->toArray()];
        } elseif(!empty($tmp_customer)){
            return ['customer'=>$tmp_customer->toArray(),'contact'=>['id'=>'']];
        }
        
    }
}