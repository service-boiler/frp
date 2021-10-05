<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventFpfFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventRunnedFilter;
use ServiceBoiler\Prf\Site\Filters\Event\RegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SortDateFromFilter;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Repositories\EventRepository;
use ServiceBoiler\Prf\Site\Repositories\EventTypeRepository;

class EventFpfController extends Controller
{
    protected $events;
    private $types;

    /**
     * Create a new controller instance.
     *
     * @param EventRepository $events
     */
    public function __construct(EventRepository $events, EventTypeRepository $types)
    {
        $this->types = $types;
        $this->events = $events;
    }

    /**
     * Show the events index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->events->trackFilter();
        $this->events->applyFilter(new SortDateFromFilter());
        $this->events->applyFilter(new EventRunnedFilter());
        $this->events->applyFilter(new EventFpfFilter());
        $this->events->pushTrackFilter(RegionSelectFilter::class);
        $this->events->pushTrackFilter(EventDateFromFilter::class);
        $this->events->pushTrackFilter(EventDateToFilter::class);

        $types = $this->types
            ->applyFilter(new Filters\EventType\SortFilter())
            ->applyFilter(new Filters\EventType\ActiveFilter())
            ->all(['event_types.*']);

        return view('site::event.index_fpf', [
            'repository' => $this->events,
            'types'      => $types->where('id', 2),
            'events'     => $this->events->paginate(config('site.per_page.event', 15), ['events.*'])
        ]);
    }

    /**
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('site::event.show', compact('event'));
    }

}
