<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Models\EsbMaintenanceDistance;
use ServiceBoiler\Prf\Site\Repositories\EsbMaintenanceDistanceRepository;

class EsbMaintenanceDistanceController extends Controller
{

    protected $esbMaintenanceDistances;
    
    use AuthorizesRequests;
    /**
     * Create a new controller instance.
     *
     * @param EsbMaintenanceDistanceRepository $esbMaintenanceDistances
     */
    public function __construct(EsbMaintenanceDistanceRepository $esbMaintenanceDistances)
    {
        $this->esbMaintenanceDistances = $esbMaintenanceDistances;
    }

   
    public function index()
    {
        $this->esbMaintenanceDistances->trackFilter();

        return view('site::admin.esb_maintenance_distance.index', [
            'repository' => $this->esbMaintenanceDistances,
            'esbMaintenanceDistances'  => $this->esbMaintenanceDistances->paginate(config('site.per_page.esb_maintenance_distances', 100), ['esb_maintenance_distances.*'])
        ]);
    }

    public function create()
    {
        $sort_order = $this->esbMaintenanceDistances->count();
        return view('site::admin.esb_maintenance_distance.create', compact('sort_order'));
    }

    
    public function store(Request $request)
    {

        $esbMaintenanceDistance = $this->esbMaintenanceDistances->create($request->except(['_token', '_method', '_create']));
        
            $redirect = redirect()->route('admin.esb-maintenance-distances.index')->with('success', trans('site::messages.created'));
        
        return $redirect;
    }


    public function edit(EsbMaintenanceDistance $esbMaintenanceDistance)
    {
        return view('site::admin.esb_maintenance_distance.edit', compact('esbMaintenanceDistance'));
    }

    
    public function update(Request $request, EsbMaintenanceDistance $esbMaintenanceDistance)
    {

        $esbMaintenanceDistance->update($request->except(['_method', '_token']));
        return redirect()->route('admin.esb-maintenance-distances.index')->with('success', trans('site::product.esb_maintenance_group_updated'));
       
    }

    public function sort(Request $request)
    {
        $sort = array_flip($request->input('sort'));

        foreach ($sort as $esbMaintenanceDistance => $sort_order) {
            
            $esbMaintenanceDistance = EsbMaintenanceDistance::find($esbMaintenanceDistance);
            $esbMaintenanceDistance->setAttribute('sort_order', $sort_order);
            $esbMaintenanceDistance->save();
        }
    }

    public function destroy(Request $request, EsbMaintenanceDistance $esbMaintenanceDistance)
    {
        $this->authorize('delete', $esbMaintenanceDistance);

        if ($esbMaintenanceDistance->delete()) {
            $json['remove'][] = '#esbMaintenanceDistance-' . $esbMaintenanceDistance->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);
    }

}