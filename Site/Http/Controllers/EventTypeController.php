<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventRunnedFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventTypeFilter;
use ServiceBoiler\Prf\Site\Filters\Event\RegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SearchCityFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SortDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\EventType\EventTypePerPageFilter;
use ServiceBoiler\Prf\Site\Models\EventType;
use ServiceBoiler\Prf\Site\Repositories\EventRepository;
use ServiceBoiler\Prf\Site\Repositories\EventTypeRepository;

class EventTypeController extends Controller
{

    private $events;
    private $event_types;

    /**
     * Create a new controller instance.
     *
     * @param EventRepository $events
     * @param EventTypeRepository $event_types
     */
    public function __construct(EventRepository $events, EventTypeRepository $event_types)
    {
        $this->event_types = $event_types;
        $this->events = $events;
    }

    /**
     * @param Request $request
     * @param EventType $event_type
     * @return \Illuminate\Http\Response
     * @internal param Event $event
     */
    public function show(Request $request, EventType $event_type)
    {
        if ($event_type->getAttribute(config('site.check_field', 'show_ferroli')) === false) {
            abort(404);
        }

        $this->events
            ->trackFilter()
            ->applyFilter(new SortDateFromFilter())
            ->applyFilter(new EventRunnedFilter())
            ->applyFilter((new EventTypeFilter())->setEventType($event_type))
            ->pushTrackFilter(RegionSelectFilter::class)
            ->pushTrackFilter(SearchCityFilter::class)
            ->pushTrackFilter(EventDateFromFilter::class)
            ->pushTrackFilter(EventDateToFilter::class)
            ->pushTrackFilter(EventTypePerPageFilter::class);

        return view('site::event_type.show', [
            'repository' => $this->events,
            'event_type' => $event_type,
            'events'     => $this->events->paginate($request->input('filter.per_page', config('site.per_page.event', 10)), ['events.*'])
        ]);
    }

}