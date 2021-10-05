<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Models\RegionBizDistrict;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\RegionBizDistrictRequest;


class RegionBizDistrictController extends Controller
{

    public function index()
    {
        $regionBizDistricts=RegionBizDistrict::query()->orderBy('sort_order')->get();
        return view('site::admin.region_biz_district.index', compact('regionBizDistricts'));
    }

    /**
     * @param RegionBizDistrictRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(RegionBizDistrictRequest $request)
    {
        return view('site::admin.region_biz_district.create');
    }

    /**
     * @param  RegionBizDistrictRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegionBizDistrictRequest $request)
    {
        $regionBizDistrict = RegionBizDistrict::create($request->input(['region_biz_district']));

        return redirect()->route('admin.region-biz-districts.index');
    }

    /**
     * @param RegionBizDistrict $region_biz_district
     * @return \Illuminate\Http\Response
     */
    public function edit(RegionBizDistrict $regionBizDistrict)
    {
        return view('site::admin.region_biz_district.edit', compact('regionBizDistrict'));
    }

    /**
     * @param  RegionBizDistrictRequest $request
     * @param  RegionBizDistrict $region_biz_district
     * @return \Illuminate\Http\Response
     */
    public function update(RegionBizDistrictRequest $request, RegionBizDistrict $region_biz_district)
    {

        $region_biz_district->update($request->input(['region_biz_district']));

        return redirect()->route('admin.region-biz-districts.index')->with('success', trans('site::region.region_biz_district_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RegionBizDistrict $region_biz_district
     * @return \Illuminate\Http\Response
     */
    public function destroy( RegionBizDistrict $region_biz_district)
    {
        if ($region_biz_district->delete()) {
            Session::flash('success', trans('site::region_biz_district.deleted'));
        } else {
            Session::flash('error', trans('site::region_biz_district.error.deleted'));
        }
        return redirect()->route('admin.region-biz-districts.index')->with('success', trans('site::region.region_biz_district_deleted') .' ' .$region_biz_district->name);
    }

}