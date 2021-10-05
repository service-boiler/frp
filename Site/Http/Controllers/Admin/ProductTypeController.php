<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ProductTypeRequest;
use ServiceBoiler\Prf\Site\Models\ProductType;
use ServiceBoiler\Prf\Site\Repositories\ProductTypeRepository;

class ProductTypeController extends Controller
{

    protected $types;

    /**
     * Create a new controller instance.
     *
     * @param ProductTypeRepository $types
     */
    public function __construct(ProductTypeRepository $types)
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

        return view('site::admin.product_type.index', [
            'repository' => $this->types,
            'types'      => $this->types->paginate(config('site.per_page.product_type', 25), ['product_types.*'])
        ]);
    }

    public function create(){
        return view('site::admin.product_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductTypeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductTypeRequest $request)
    {

        //dd($request->all());
        $product_type = $this->types->create($request->except(['_token', '_method', '_create']));
        if ($request->input('_create') == 1) {
            $redirect = redirect()->route('admin.product_types.create')->with('success', trans('site::product_type.created'));
        } else {
            $redirect = redirect()->route('admin.product_types.show', $product_type)->with('success', trans('site::product_type.created'));
        }

        return $redirect;
    }

    public function show(ProductType $product_type)
    {
        return view('site::admin.product_type.show', compact('product_type'));
    }

    public function edit(ProductType $product_type)
    {
        return view('site::admin.product_type.edit', compact('product_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductTypeRequest $request
     * @param  ProductType $product_type
     * @return \Illuminate\Http\Response
     */
    public function update(ProductTypeRequest $request, ProductType $product_type)
    {

        $product_type->update($request->except(['_method', '_token', '_stay']));

        if ($request->input('_stay') == 1) {

            $redirect = redirect()->route('admin.product_types.edit', $product_type)->with('success', trans('site::product_type.updated'));
        } else {
            $redirect = redirect()->route('admin.product_types.show', $product_type)->with('success', trans('site::product_type.updated'));
        }

        return $redirect;
    }


}