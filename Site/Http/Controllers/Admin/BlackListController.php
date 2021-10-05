<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\BlackListRequest;
use ServiceBoiler\Prf\Site\Models\BlackList;
use ServiceBoiler\Prf\Site\Repositories\BlackListRepository;

class BlackListController extends Controller
{

    protected $blackList;

    /**
     * Create a new controller instance.
     *
     * @param BlackListRepository $blackList
     */
    public function __construct(BlackListRepository $blackList)
    {
        $this->blackList = $blackList;
    }

    /**
     * Show the shop index blackList
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->blackList->trackFilter();

        return view('site::admin.black_list.index', [
            'repository' => $this->blackList,
            'blackList'  => $this->blackList->paginate(config('site.per_page.blackList', 100), ['black_list_addresses.*'])
        ]);
    }

    public function create()
    {
        return view('site::admin.black_list.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlackListRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlackListRequest $request)
    {

        //dd($request->all());
        $blackList = $this->blackList->create($request->except(['_token', '_method', '_create']));
        
        $redirect = redirect()->route('admin.black-list.index')->with('success', trans('site::admin.black_list.created'));
        

        return $redirect;
    }


    /**
     * @param BlackList $blackList
     * @return \Illuminate\Http\Response
     */
    public function edit(BlackList $blackList)
    {
        return view('site::admin.black_list.edit', compact('blackList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlackListRequest $request
     * @param  BlackList $blackList
     * @return \Illuminate\Http\Response
     */
    public function update(BlackListRequest $request, BlackList $blackList)
    {
        $blackList->update(array_merge(
            $request->input(),
            ['active' => $request->filled('active')]
        ));
        
        if ($request->input('_stay') == 1) {

            $redirect = redirect()->route('admin.black-list.edit', $blackList)->with('success', trans('site::admin.black_list.updated'));
        } else {
            $redirect = redirect()->route('admin.black-list.index')->with('success', trans('site::admin.black_list.updated'));
        }

        return $redirect;
    }

    public function show(BlackList $blackList)
    {

        return view('site::admin.black_list.show', compact('blackList'));
    }

    public function destroy(BlackList $blackList)
    {

        if ($blackList->delete()) {
            return redirect()->route('admin.black-list.index')->with('success', trans('site::admin.black_list.deleted'));
        } else {
            return redirect()->route('admin.black-list.edit', $blackList)->with('error', trans('site::admin.black_list.error.deleted'));
        }
    }

}