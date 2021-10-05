<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\RegionItalyDistrict;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\RegionRequest;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;

class RegionController extends Controller
{
    /**
     * @var RegionRepository
     */
    protected $regions;

    public function __construct(RegionRepository $regions)
    {
        $this->regions = $regions;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(RegionRequest $request)
    {
        
		$this->regions->applyFilter(new SortFilter());
		
        return view('site::admin.region.index', [
            'repository' => $this->regions,
            'regions'      => $this->regions->paginate(config('site.per_page.region', 300), ['regions.*'])
            
			
        ]);
    }


    /**
     * @param Region $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
       
		$region_italy_districts = RegionItalyDistrict::get();

        return view('site::admin.region.edit', compact('region','region_italy_districts'));
    }

    /**
     * @param  RegionRequest $request
     * @param  Region $region
     * @return \Illuminate\Http\Response
     */
    public function update(RegionRequest $request, Region $region)
    {

        $region->update($request->input(['region']));

        return redirect()->route('admin.regions.index')->with('success', trans('site::region.region_updated'));
    }


}