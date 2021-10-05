<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ContragentRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\ContragentType;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Repositories\ContragentRepository;

class ContragentController extends Controller
{

    use AuthorizesRequests;

    protected $contragents;

    /**
     * Create a new controller instance.
     *
     * @param ContragentRepository $contragents
     */
    public function __construct(ContragentRepository $contragents)
    {
        $this->contragents = $contragents;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Contragent::class);
        $this->contragents->trackFilter();
        $this->contragents->applyFilter(new BelongsUserFilter());

        return view('site::contragent.index', [
            'repository'  => $this->contragents,
            'contragents' => $this->contragents->paginate(config('site.per_page.contragent', 10), ['contragents.*'])
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Contragent::class);
        $countries = Country::query()->where('enabled', 1)->orderBy('sort_order')->get();
        $regions = Region::query()->whereHas('country', function ($query) {
            $query->where('enabled', 1);
        })->orderBy('name')->get();

        $types = ContragentType::all();

        return view('site::contragent.create', compact(
            'countries',
            'types',
            'regions'
        ));
    }

    /**
     * @param ContragentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContragentRequest $request)
    {

        /** @var Contragent $contragent */
        $request->user()->contragents()->save($contragent = Contragent::query()->create($request->input('contragent')));
        $contragent->addresses()->saveMany([
            Address::query()->create($request->input('address.legal')),
            Address::query()->create($request->input('address.postal')),
        ]);
        if ($request->input('_create') == 1) {
            $redirect = redirect()->route('contragents.create')->with('success', trans('site::contragent.created'));
        } else {
            $redirect = redirect()->route('contragents.index')->with('success', trans('site::contragent.created'));
        }

        return $redirect;
    }

    /**
     * @param Contragent $contragent
     * @return \Illuminate\Http\Response
     */
    public function edit(Contragent $contragent)
    {
        $this->authorize('edit', $contragent);
        $types = ContragentType::all();
        $regions=Region::query()->where('country_id',config('site.country'))->orderBy('name')->get();
        $addressLegal=$contragent->addressLegal();

        return view('site::contragent.edit', compact('contragent', 'types','regions','addressLegal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContragentRequest $request
     * @param  Contragent $contragent
     * @return \Illuminate\Http\Response
     */
    public function update(ContragentRequest $request, Contragent $contragent)
    {

        $contragent->update($request->input('contragent'));
        if($request->input('address.legal')){

            if($address=$contragent->addressLegal()) {

                $address->update($request->input('address.legal'));
            } else {
                $legal = Address::query()->create($request->input('address.legal'));
                $contragent->addresses()->saveMany([$legal]);
            }
        }
        return redirect()->route('contragents.show', $contragent)->with('success', trans('site::contragent.updated'));
    }

    /**
     * @param Contragent $contragent
     * @return \Illuminate\Http\Response
     */
    public function show(Contragent $contragent)
    {
        return view('site::contragent.show', compact('contragent'));
    }

}