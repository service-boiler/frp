<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\AddressRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Region;

class ContragentAddressController extends Controller
{

    use AuthorizesRequests;

    /**
     * @param Contragent $contragent
     * @param Address $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Contragent $contragent, Address $address)
    {

        $countries = Country::query()->where('enabled', 1)->orderBy('sort_order')->get();
        $regions = Region::query()->whereHas('country', function ($query) {
            $query->where('enabled', 1);
        })->orderBy('name')->get();

        return view('site::admin.contragent.address.edit', compact(
            'address',
            'contragent',
            'countries',
            'regions'
        ));
    }

    /**
     * @param  AddressRequest $request
     * @param Contragent $contragent
     * @param  Address $address
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, Contragent $contragent, Address $address)
    {
        $address->update($request->input('address'));

        return redirect()->route('admin.contragents.show', $contragent)->with('success', trans('site::address.updated'));
    }

}