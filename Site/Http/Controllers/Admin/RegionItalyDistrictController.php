<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Models\RegionItalyDistrict;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\RegionItalyDistrictRequest;
use ServiceBoiler\Prf\Site\Repositories\RegionItalyDistrictRepository;

class RegionItalyDistrictController extends Controller
{
    /**
     * @var RegionItalyDistrictRepository
     */
    protected $region_italy_districts;

    public function __construct(RegionItalyDistrictRepository $region_italy_districts)
    {
        $this->region_italy_districts = $region_italy_districts;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(RegionItalyDistrictRequest $request)
    {
        
        return view('site::admin.region_italy_district.index', [
            'repository' => $this->region_italy_districts,
            'region_italy_districts'      => $this->region_italy_districts->paginate(config('site.per_page.region', 100), ['region_italy_districts.*'])
        ]);
    }

    /**
     * @param RegionItalyDistrictRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(RegionItalyDistrictRequest $request)
    {
        return view('site::admin.region_italy_district.create');
    }

    /**
     * @param  RegionItalyDistrictRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegionItalyDistrictRequest $request)
    {
        $region_italy_district = $this->region_italy_districts->create($request->input(['region_italy_district']));

        return redirect()->route('admin.region-italy-districts.index');
    }

    /**
     * @param RegionItalyDistrict $region_italy_district
     * @return \Illuminate\Http\Response
     */
    public function edit(RegionItalyDistrict $region_italy_district)
    {
        return view('site::admin.region_italy_district.edit', compact('region_italy_district'));
    }

    /**
     * @param  RegionItalyDistrictRequest $request
     * @param  RegionItalyDistrict $region_italy_district
     * @return \Illuminate\Http\Response
     */
    public function update(RegionItalyDistrictRequest $request, RegionItalyDistrict $region_italy_district)
    {

        $region_italy_district->update($request->input(['region_italy_district']));

        return redirect()->route('admin.region-italy-districts.index')->with('success', trans('site::region.region_italy_district_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RegionItalyDistrict $region_italy_district
     * @return \Illuminate\Http\Response
     */
    public function destroy( RegionItalyDistrict $region_italy_district)
    {
        if ($region_italy_district->delete()) {
            Session::flash('success', trans('site::region_italy_district.deleted'));
        } else {
            Session::flash('error', trans('site::region_italy_district.error.deleted'));
        }
        return redirect()->route('admin.region-italy-districts.index')->with('success', trans('site::region.region_italy_district_deleted') .' ' .$region_italy_district->name);
    }

}