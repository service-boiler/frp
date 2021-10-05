<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\ShowFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventRunnedFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventTypeFilter;
use ServiceBoiler\Prf\Site\Filters\Event\RegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\TypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SearchCityFilter;
use ServiceBoiler\Prf\Site\Filters\Event\WebinarSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SortDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SortDateFromDescFilter;
use ServiceBoiler\Prf\Site\Filters\EventType\EventTypePerPageFilter;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\EventType;
use ServiceBoiler\Prf\Site\Repositories\EventRepository;
use ServiceBoiler\Prf\Site\Repositories\EventTypeRepository;

class EventController extends Controller
{
	public function __construct(EventRepository $events, EventTypeRepository $event_types)
    {
        $this->event_types = $event_types;
        $this->events = $events;
    }

    /**
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        if($event->getAttribute(config('site.check_field')) === false){
            abort(404);
        }
        return view('site::event.show', compact('event'));
    }

	public function index()
    {

        $this->events->trackFilter();
        $this->events->applyFilter(new SortDateFromDescFilter());
        $this->events->applyFilter(new EventRunnedFilter());
        $this->events->applyFilter(new ShowFilter());
        $this->events->pushTrackFilter(RegionSelectFilter::class);
        $this->events->pushTrackFilter(EventDateFromFilter::class);
        $this->events->pushTrackFilter(EventDateToFilter::class);
       // $this->events->pushTrackFilter(WebinarSelectFilter::class);
        //$this->events->pushTrackFilter(TypeSelectFilter::class);

       $this->event_types;

        return view('site::event.index', [
            'repository' => $this->events,
            'events'     => $this->events->paginate(config('site.per_page.event', 90), ['events.*']),
	    'types'	 => $this->event_types->all(['event_types.*'])
        ]);
    }



}