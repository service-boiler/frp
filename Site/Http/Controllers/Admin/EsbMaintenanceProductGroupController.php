<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Models\EsbMaintenanceProductGroup;
use ServiceBoiler\Prf\Site\Repositories\EsbMaintenanceProductGroupRepository;

class EsbMaintenanceProductGroupController extends Controller
{

    protected $esbMaintenanceProductGroups;
    
    use AuthorizesRequests;
    /**
     * Create a new controller instance.
     *
     * @param EsbMaintenanceProductGroupRepository $esbMaintenanceProductGroups
     */
    public function __construct(EsbMaintenanceProductGroupRepository $esbMaintenanceProductGroups)
    {
        $this->esbMaintenanceProductGroups = $esbMaintenanceProductGroups;
    }

   
    public function index()
    {
        $this->esbMaintenanceProductGroups->trackFilter();

        return view('site::admin.esb_maintenance_product_group.index', [
            'repository' => $this->esbMaintenanceProductGroups,
            'esbMaintenanceProductGroups'  => $this->esbMaintenanceProductGroups->paginate(config('site.per_page.esb_maintenance_product_groups', 100), ['esb_maintenance_product_groups.*'])
        ]);
    }

    public function create()
    {
        $sort_order = $this->esbMaintenanceProductGroups->count();
        return view('site::admin.esb_maintenance_product_group.create', compact('sort_order'));
    }

    
    public function store(Request $request)
    {

        $esbMaintenanceProductGroup = $this->esbMaintenanceProductGroups->create($request->except(['_token', '_method', '_create']));
        
            $redirect = redirect()->route('admin.esb-maintenance-product-groups.index')->with('success', trans('site::esbMaintenanceProductGroup.created'));
        
        return $redirect;
    }


    public function edit(EsbMaintenanceProductGroup $esbMaintenanceProductGroup)
    {
        return view('site::admin.esb_maintenance_product_group.edit', compact('esbMaintenanceProductGroup'));
    }

    
    public function update(Request $request, EsbMaintenanceProductGroup $esbMaintenanceProductGroup)
    {

        $esbMaintenanceProductGroup->update($request->except(['_method', '_token']));
        return redirect()->route('admin.esb-maintenance-product-groups.index')->with('success', trans('site::product.esb_maintenance_group_updated'));
       
    }

    public function sort(Request $request)
    {
        $sort = array_flip($request->input('sort'));

        foreach ($sort as $esbMaintenanceProductGroup => $sort_order) {
            
            $esbMaintenanceProductGroup = EsbMaintenanceProductGroup::find($esbMaintenanceProductGroup);
            $esbMaintenanceProductGroup->setAttribute('sort_order', $sort_order);
            $esbMaintenanceProductGroup->save();
        }
    }

    public function destroy(Request $request, EsbMaintenanceProductGroup $esbMaintenanceProductGroup)
    {
        $this->authorize('delete', $esbMaintenanceProductGroup);

        if ($esbMaintenanceProductGroup->delete()) {
            $json['remove'][] = '#esbMaintenanceProductGroup-' . $esbMaintenanceProductGroup->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);
    }

}