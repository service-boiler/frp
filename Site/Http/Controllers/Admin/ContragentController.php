<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Exports\Excel\ContragentExcel;
use ServiceBoiler\Prf\Site\Filters\Contragent\ContragentPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Contragent\ContragentUserSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Organization\HasAccountFilter;
use ServiceBoiler\Prf\Site\Filters\Organization\SortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ContragentRequest;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Repositories\ContragentRepository;
use ServiceBoiler\Prf\Site\Repositories\ContragentTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\OrganizationRepository;

class ContragentController extends Controller
{
    /**
     * @var ContragentRepository
     */
    protected $contragents;
    /**
     * @var ContragentTypeRepository
     */
    protected $types;
    /**
     * @var OrganizationRepository
     */
    private $organizations;

    /**
     * Create a new controller instance.
     *
     * @param ContragentRepository $contragents
     * @param ContragentTypeRepository $types
     * @param OrganizationRepository $organizations
     */
    public function __construct(
        ContragentRepository $contragents,
        ContragentTypeRepository $types,
        OrganizationRepository $organizations
    )
    {
        $this->contragents = $contragents;
        $this->types = $types;
        $this->organizations = $organizations;
    }

    /**
     * Show the user contragent
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->contragents->trackFilter();
        $this->contragents->pushTrackFilter(ContragentUserSelectFilter::class);
        $this->contragents->pushTrackFilter(ContragentPerPageFilter::class);

        if ($request->has('excel')) {
            (new ContragentExcel())->setRepository($this->contragents)->render();
        }

        return view('site::admin.contragent.index', [
            'repository'  => $this->contragents,
            'contragents' => $this->contragents->paginate($request->input('filter.per_page', config('site.per_page.contragent', 10)), ['contragents.*'])
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param Contragent $contragent
     * @return \Illuminate\Http\Response
     */
    public function show(Contragent $contragent)
    {
        return view('site::admin.contragent.show', ['contragent' => $contragent]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contragent $contragent
     * @return \Illuminate\Http\Response
     */
    public function edit(Contragent $contragent)
    {
        $types = $this->types->all();
        $this->organizations->applyFilter(new SortFilter());
        $this->organizations->applyFilter(new HasAccountFilter());
        $organizations = $this->organizations->all();

        return view('site::admin.contragent.edit', compact('contragent', 'types', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContragentRequest $request
     * @param  Contragent $contragent
     * @return \Illuminate\Http\Response
     */
    public function update(ContragentRequest $request, Contragent $contragent)
    {
        $contragent->update($request->input('contragent'));

        return redirect()->route('admin.contragents.show', $contragent)->with('success', trans('site::contragent.updated'));
    }
}