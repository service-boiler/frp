<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\CustomerContactRequest;
use ServiceBoiler\Prf\Site\Models\Customer;
use ServiceBoiler\Prf\Site\Models\CustomerContact;

class CustomerContactController extends Controller
{

    use AuthorizesRequests;

    
    public function show(CustomerContact $contact, Request $request)
    {
        $view = $request->ajax() ? 'site::admin.customer.contact.show_modal' : 'site::admin.contact.customer.show';
         return view($view, compact('contact'));
    }
    
    public function create(Customer $customer)
    {
        
        return view('site::admin.customer.contact.create', compact('customer'));
        
    }
    
    public function edit(Customer $customer, CustomerContact $contact)
    {
        
        return view('site::admin.customer.contact.edit', compact('customer','contact'));
        
    }
    public function store(CustomerContactRequest $request, Customer $customer)
    {
        $customer->contacts()->save($contact = CustomerContact::query()->create(array_merge($request->input('contact'),
                                                                                             ['lpr' => $request->filled('lpr')])));
        
        return redirect()->route('admin.customers.show', $customer)->with('success', trans('site::admin.customer.updated'));
        
    }
    public function update(CustomerContactRequest $request, Customer $customer, CustomerContact $contact)
    {
        $contact->update(array_merge($request->input('contact'),['lpr' => $request->filled('lpr')]));
        
        return redirect()->route('admin.customers.show', $customer)->with('success', trans('site::admin.customer.updated'));
        
    }
    
    public function destroy(Customer $customer, CustomerContact $contact)
    {
        if ($contact->delete()) {
            $json['remove'][] = '#contact-' . $contact->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);
    }


}