<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Events\ActMountingCreateEvent;
use ServiceBoiler\Prf\Site\Filters\Act\ActPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingForActFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ActRequest;
use ServiceBoiler\Prf\Site\Models\Act;
use ServiceBoiler\Prf\Site\Models\ActDetail;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\Mounting;
use ServiceBoiler\Prf\Site\Repositories\ActRepository;
use ServiceBoiler\Prf\Site\Repositories\MountingRepository;

class ActController extends Controller
{

    use AuthorizesRequests;
    /**
     * @var ActRepository
     */
    protected $acts;
    /**
     * @var MountingRepository
     */
    private $mountings;

    /**
     * Create a new controller instance.
     *
     * @param ActRepository $acts
     * @param MountingRepository $mountings
     */
    public function __construct(ActRepository $acts, MountingRepository $mountings)
    {
        $this->acts = $acts;
        $this->mountings = $mountings;
    }

    /**
     * Show the user profile
     *
     * @param ActRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ActRequest $request)
    {

        $this->acts->trackFilter();
        $this->acts->applyFilter(new BelongsUserFilter());
        $this->acts->pushTrackFilter(ActPerPageFilter::class);

        return view('site::act.index', [
            'repository' => $this->acts,
            'acts'       => $this->acts->paginate($request->input('filter.per_page', config('site.per_page.mounting', 10)), ['acts.*'])
        ]);
    }

    public function show(Act $act)
    {
        $this->authorize('view', $act);

        return view('site::act.show', compact('act'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Act::class);
        $this->mountings->trackFilter();
        $this->mountings->applyFilter(new BelongsUserFilter());
        $this->mountings->applyFilter(new MountingForActFilter());
        $mountings = $this->mountings->all();
        $contragents = collect([]);
        foreach ($mountings->groupBy('contragent_id') as $contragent_id => $mounts) {
            $contragents->put($contragent_id, Contragent::query()->find($contragent_id));
        }
        $mountings_group = $mountings->groupBy('contragent_id');

        return view('site::act.create', compact('mountings_group', 'contragents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ActRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActRequest $request)
    {
        $acts = collect([]);
        foreach ($request->input('mountings') as $contragent_id => $mountings) {
            $contragent = Contragent::query()->find($contragent_id);
            /** @var Act $act */
            $request->user()->acts()->save($act = Act::query()->create([
                'contragent_id' => $contragent_id,
                'type_id'       => 2
            ]));
            $act->details()->saveMany([
                new ActDetail($contragent->organization->toArray()),
                new ActDetail($contragent->toArray())
            ]);
            foreach ($mountings as $mounting_id) {
                /** @var Mounting $mounting */
                $mounting = Mounting::query()->find($mounting_id);

                $mounting->act()->associate($act);

                $mounting->save();
            }
            $acts->push($act);
        }
        event(new ActMountingCreateEvent($request->user(), $acts));

        return redirect()->route('acts.index')->with('success', trans('site::act.created'));
    }

    /**
     * @param Act $act
     * @return \Illuminate\Http\Response
     */
    public function edit(Act $act)
    {
        $this->authorize('update', $act);

        return view('site::act.edit', compact('act'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ActRequest $request
     * @param  Act $act
     * @return \Illuminate\Http\Response
     */
    public function update(ActRequest $request, Act $act)
    {
        $act->update($request->input(['act']));

        return redirect()->route('acts.show', $act)->with('success', trans('site::act.updated'));
    }

}