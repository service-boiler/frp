<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Events\MemberCreateEvent;
use ServiceBoiler\Prf\Site\Filters\Member\MemberDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Member\MemberDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Member\MemberPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Member\MemberRegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Member\MemberRunnedFilter;
use ServiceBoiler\Prf\Site\Filters\Member\MemberSearchCityFilter;
use ServiceBoiler\Prf\Site\Filters\Member\MemberTypeSelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\MemberRequest;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\EventType;
use ServiceBoiler\Prf\Site\Models\Member;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Repositories\EventRepository;
use ServiceBoiler\Prf\Site\Repositories\EventTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\MemberRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;

class MemberController extends Controller
{

    protected $members;
    /**
     * @var RegionRepository
     */
    private $regions;
    /**
     * @var EventRepository
     */
    private $events;
    /**
     * @var EventTypeRepository
     */
    private $types;

    /**
     * Create a new controller instance.
     *
     * @param MemberRepository $members
     * @param RegionRepository $regions
     * @param EventRepository $events
     * @param EventTypeRepository $types
     */
    public function __construct(
        MemberRepository $members,
        RegionRepository $regions,
        EventRepository $events,
        EventTypeRepository $types

    )
    {
        $this->members = $members;
        $this->regions = $regions;
        $this->events = $events;
        $this->types = $types;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->members->trackFilter();
        $this->members->applyFilter(new MemberRunnedFilter());
        $this->members
            ->pushTrackFilter(MemberSearchCityFilter::class)
            ->pushTrackFilter(MemberRegionSelectFilter::class)
            ->pushTrackFilter(MemberTypeSelectFilter::class)
            ->pushTrackFilter(MemberDateFromFilter::class)
            ->pushTrackFilter(MemberDateToFilter::class)
            ->pushTrackFilter(MemberPerPageFilter::class);

        return view('site::member.index', [
            'repository' => $this->members,
            'members'    => $this->members->paginate($request->input('filter.per_page', config('site.per_page.member', 10)), ['members.*'])
        ]);
    }

    /**
     * @param EventType $event_type
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $regions = Region::query()->whereHas('country', function ($query) {
            $query->where('enabled', 1);
        })->orderBy('name')->get();
        $event_types = EventType::query()->where(config('site.check_field'), 1)->get();
        return view('site::member.create', compact('countries', 'regions', 'event_types'));
    }

    /**
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function register(Event $event)
    {
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();

        return view('site::member.register', compact('event', 'countries'));
    }

    /**
     * @param  MemberRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {

        $member = $this->members->create($request->input('member'));

        if ($request->filled('participant')) {
            $member->participants()->createMany($request->input('participant'));
        }

        event(new MemberCreateEvent($member));

        if ($member->event()->exists()) {
            return redirect()->route('events.show', $member->event)->with('success', trans('site::member.confirm_email', ['email' => $member->email]));
        } else {
            return redirect()->route('events.index')->with('success', trans('site::member.confirm_email', ['email' => $member->email]));
        }

    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function confirm($token)
    {
        Member::query()->where('verify_token', $token)->firstOrFail()->hasVerified();

        return redirect()->route('members.index')->with('success', trans('site::member.confirmed_email'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function participant()
    {
        $random = mt_rand(10000, 50000);

        return view('site::participant.create', compact('random'));
    }
}