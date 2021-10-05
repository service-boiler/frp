<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\PriceTypeRequest;
use ServiceBoiler\Prf\Site\Models\PriceType;
use ServiceBoiler\Prf\Site\Repositories\PriceTypeRepository;

class PriceTypeController extends Controller
{

    protected $types;

    /**
     * Create a new controller instance.
     *
     * @param PriceTypeRepository $types
     */
    public function __construct(PriceTypeRepository $types)
    {
        $this->types = $types;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->types->trackFilter();

        return view('site::admin.price_type.index', [
            'repository' => $this->types,
            'types'      => $this->types->paginate(config('site.per_page.price_type', 30), ['price_types.*'])
        ]);
    }

    public function show(PriceType $price_type)
    {
        return view('site::admin.price_type.show', compact('price_type'));
    }


    public function edit(PriceType $price_type)
    {
        return view('site::admin.price_type.edit', compact('price_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PriceTypeRequest $request
     * @param  PriceType $price_type
     * @return \Illuminate\Http\Response
     */
    public function update(PriceTypeRequest $request, PriceType $price_type)
    {
        $price_type->update($request->input('price_type'));
        if ($request->input('_stay') == 1) {
            $redirect = redirect()->route('admin.price_types.edit', $price_type)->with('success', trans('site::price_type.updated'));
        } else {
            $redirect = redirect()->route('admin.price_types.show', $price_type)->with('success', trans('site::price_type.updated'));
        }
//	dd($request->input('price_type'));
        return $redirect;
    }

}
