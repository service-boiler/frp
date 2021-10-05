<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Events\ActExport;
use ServiceBoiler\Prf\Site\Events\ActRepairCreateEvent;
use ServiceBoiler\Prf\Site\Filters\Act\ActPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\User\HasApprovedRepairFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ActRequest;
use ServiceBoiler\Prf\Site\Models\Act;
use ServiceBoiler\Prf\Site\Models\ActDetail;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\ActRepository;
use ServiceBoiler\Prf\Site\Repositories\RepairRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;

class ActController extends Controller
{

    use AuthorizesRequests;
    /**
     * @var ActRepository
     */
    protected $acts;
    /**
     * @var RepairRepository
     */
    protected $repairs;
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * Create a new controller instance.
     *
     * @param ActRepository $acts
     * @param RepairRepository $repairs
     * @param UserRepository $users
     */
    public function __construct(
        ActRepository $acts,
        RepairRepository $repairs,
        UserRepository $users
    )
    {
        $this->acts = $acts;
        $this->repairs = $repairs;
        $this->users = $users;
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
        $this->acts->pushTrackFilter(ActPerPageFilter::class);

        return view('site::admin.act.index', [
            'repository' => $this->acts,
            'acts'       => $this->acts->paginate($request->input('filter.per_page', config('site.per_page.act', 200)), ['acts.*'])
        ]);
    }

    public function show(Act $act)
    {   
        $this->authorize('view', $act);
        return view('site::admin.act.show', compact('act'));
    }

    public function create()
    {
        $repository = $this->repairs;
        $users = $this->users->applyFilter(new HasApprovedRepairFilter())->all();

        return view('site::admin.act.create', compact('repository', 'users'));
    }

    /**
     * @param ActRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ActRequest $request)
    {

        foreach ($request->input('repair') as $user_id => $contragents) {
            /** @var User $user */
            $user = User::query()->find($user_id);
            $acts = collect([]);
            foreach ($contragents as $contragent_id => $repairs) {
                $contragent = Contragent::query()->find($contragent_id);
                /** @var Act $act */
                $user->acts()->save($act = Act::create([
                    'contragent_id' => $contragent_id,
                    'type_id'       => 1
                ]));

                $act->details()->saveMany([
                    new ActDetail($contragent->organization->toArray()),
                    new ActDetail($contragent->toArray())
                ]);
                foreach ($repairs as $repair_id) {
                    /** @var Repair $repair */
                    $repair = Repair::query()->find($repair_id);

                    $repair->act()->associate($act);

                    $repair->save();

                }
                $acts->push($act);
            }
            event(new ActRepairCreateEvent($user, $acts));
        }

        return redirect()->route('admin.acts.index')->with('success', trans('site::act.created'));
    }

    /**
     * @param Act $act
     * @return \Illuminate\Http\Response
     */
    public function edit(Act $act)
    {
        $this->authorize('update', $act);

        return view('site::admin.act.edit', compact('act'));
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
        $act->update(array_merge($request->input(['act']), [
            'received' => $request->filled('act.received'),
            'paid'     => $request->filled('act.paid')
        ]));

        return redirect()->route('admin.acts.show', $act)->with('success', trans('site::act.updated'));
    }
    
    public function destroy(Act $act)
    {

        if ($act->delete()) {
            Session::flash('success', trans('site::act.deleted'));
        } else {
            Session::flash('error', trans('site::act.error.deleted'));
        }
        $json['redirect'] = route('admin.acts.index');

        return response()->json($json);
    }

	/**
	 * @param Act $act
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function schedule(Act $act)
    {
        $this->authorize('schedule', $act);
        event(new ActExport($act));

        return redirect()->route('admin.acts.show', $act)->with('success', trans('site::schedule.created'));

    }
}