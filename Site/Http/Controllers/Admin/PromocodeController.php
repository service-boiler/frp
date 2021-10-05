<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use ServiceBoiler\Prf\Site\Models\Promocode;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\PromocodeRequest;
use ServiceBoiler\Prf\Site\Repositories\PromocodeRepository;

class PromocodeController extends Controller
{

    protected $promocodes;

    /**
     * Create a new controller instance.
     *
     * @param PromocodeRepository $promocodes
     */
    public function __construct(PromocodeRepository $promocodes)
    {
        $this->promocodes = $promocodes;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->promocodes->trackFilter();

        return view('site::admin.promocode.index', [
            'repository' => $this->promocodes,
            'promocodes'  => $this->promocodes->paginate(config('site.per_page.promocode', 100), ['promocodes.*'])
        ]);
    }

   public function create(PromocodeRequest $request)
    {   
        $view = $request->ajax() ? 'site::admin.promocode.form.create' : 'site::admin.promocode.create';

        return view($view);
    
    }
   
   
   public function store(PromocodeRequest $request)
    {   
        $token = Str::uuid()->toString();
        $promocodeData = array_merge($request->input('promocode'), ['token'=>$token]);
        $promocode = $this->promocodes->create($promocodeData);
        
        
        if ($request->ajax()) {
            $promocodes = Promocode::query()->orderBy('name')->get();
            Session::flash('success', trans('site::admin.promocodes.created'));

            return response()->json([
                'update' => [
                    '#promocode_id' => view('site::admin.promocode.options')
                        ->with('promocodes', $promocodes)
                        ->with('promocode_id', $promocode->getKey())
                        ->render()
                ],
                'append' => [
                    '#toasts' => view('site::components.toast')
                        ->with('message', trans('site::admin.promocodes.created'))
                        ->with('status', 'success')
                        ->render()
                ]
            ]);
        }
        
        
        
        return $redirect = redirect()->route('admin.promocodes.index')->with('success', trans('site::admin.promocodes.created'));
    }
    
  /**
     * @param Promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit(Promocode $promocode)
    {
        return view('site::admin.promocode.edit', compact('promocode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PromocodeRequest $request
     * @param  Promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(PromocodeRequest $request, Promocode $promocode)
    {
       
        $promocode->update($request->input('promocode'));
        $redirect = redirect()->route('admin.promocodes.edit', $promocode)->with('success', trans('site::admin.promocode_updated'));
        return $redirect;
    }
    
      public function destroy(promocode $promocode)
    {

        if ($promocode->delete()) {
            return redirect()->route('admin.promocodes.index')->with('success', trans('site::admin.promocode_deleted'));
        } else {
            return redirect()->route('admin.promocodes.show', $promocode)->with('error', trans('site::admin.block.promocode.error.deleted'));
        }
    }

 
}