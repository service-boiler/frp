<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventRunnedFilter;
use ServiceBoiler\Prf\Site\Filters\Event\RegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SortDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventIndexDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\WebinarIndexDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\ShowFilter;
use ServiceBoiler\Prf\Site\Filters\Event\TypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\WebinarSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Variable\AcademyFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\SortAscFilter;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\EventType;
use ServiceBoiler\Prf\Site\Models\Webinar;
//use ServiceBoiler\Prf\Site\Models\Variable;
use ServiceBoiler\Prf\Site\Repositories\EventRepository;
use ServiceBoiler\Prf\Site\Repositories\WebinarRepository;
use ServiceBoiler\Prf\Site\Repositories\EventTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\VariableRepository;

class AcademyEventsController extends Controller
{
    protected $events;
    private $variables;
    private $types;

    /**
     * Create a new controller instance.
     *
     * @param EventRepository $events
     */
    public function __construct(
        EventRepository $events, 
        EventTypeRepository $types,
        WebinarRepository $webinars,
        VariableRepository $variables,
        HeadBannerBlockRepository $headBannerBlocks)
    {
        $this->types = $types;
        $this->events = $events;
        $this->webinars = $webinars;
        $this->variables = $variables;
        $this->headBannerBlocks = $headBannerBlocks;
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
        $this->events->applyFilter(new EventIndexDateFromFilter());
        $this->events->applyFilter(new ShowFilter());
        $this->events->pushTrackFilter(RegionSelectFilter::class);
        $this->events->pushTrackFilter(EventDateFromFilter::class);
        $this->events->pushTrackFilter(EventDateToFilter::class);
        //$this->events->pushTrackFilter(WebinarSelectFilter::class);
        //$this->events->pushTrackFilter(TypeSelectFilter::class);

        
        $this->webinars->applyFilter(new WebinarIndexDateFromFilter());
        $this->webinars->applyFilter(new SortAscFilter());
        
        $types = $this->types
            ->applyFilter(new Filters\EventType\SortFilter())
            ->applyFilter(new Filters\EventType\ActiveFilter())
            ->all(['event_types.*']);
				
        $headBannerBlocks = $this->headBannerBlocks
            ->applyFilter(new Filters\ShowFilter())
            ->applyFilter(new Filters\BySortOrderFilter())
            ->applyFilter(new Filters\PathUrlFilter())
            ->all(['head_banner_blocks.*']);
			
		
        $variables = $this->variables
            ->applyFilter(new Filters\Variable\AcademyFilter())
            ->all(['variables.*']);
        
        $vars = $variables->pluck('value','name');
      
        
    	
        return view('site::event.index_academy', [
            'repository' => $this->events,
            'types'      => $types->where('id', 1),
            'events'     => $this->events->paginate(3, ['events.*']),
            'webinars'     => $this->webinars
                                ->applyFilter(new Filters\Webinar\WebinarTypePublicFilter())
                                ->applyFilter(new Filters\EnabledFilter())
                                ->applyFilter(new WebinarIndexDateFromFilter())
                                ->applyFilter(new Filters\Webinar\SortFilter())
                                ->paginate(5, ['webinars.*'])->sortBy('datetime'),
            'vars'     => $vars,
            'headBannerBlocks' => $headBannerBlocks
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