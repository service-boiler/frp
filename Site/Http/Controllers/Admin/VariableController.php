<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\VariableRequest;
use ServiceBoiler\Prf\Site\Models\Variable;
use ServiceBoiler\Prf\Site\Repositories\VariableRepository;

class VariableController extends Controller
{

    protected $variables;

    /**
     * Create a new controller instance.
     *
     * @param VariableRepository $variables
     */
    public function __construct(VariableRepository $variables)
    {
        $this->variables = $variables;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->variables->trackFilter();

        return view('site::admin.variable.index', [
            'repository' => $this->variables,
            'variables'  => $this->variables->paginate(config('site.per_page.variable', 100), ['variables.*'])
        ]);
    }

  
  /**
     * @param Variable $variable
     * @return \Illuminate\Http\Response
     */
    public function edit(Variable $variable)
    {
        return view('site::admin.variable.edit', compact('variable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  VariableRequest $request
     * @param  Variable $variable
     * @return \Illuminate\Http\Response
     */
    public function update(VariableRequest $request, Variable $variable)
    {
       
        $variable->update($request->except(['_method', '_token', '_stay']));
        if($variable->name == 'currency_static_euro'){
            $redirect = redirect()->route('admin.currencies.index')->with('success', trans('site::admin.variable_updated'));
        } else {
            $redirect = redirect()->route('admin.variables.edit', $variable)->with('success', trans('site::admin.variable_updated'));
        }
        return $redirect;
    }

 
}