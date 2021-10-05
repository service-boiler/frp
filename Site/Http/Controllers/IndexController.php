<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Event\EventRunnedFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SortDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\ShowFilter;
use ServiceBoiler\Prf\Site\Filters\PathUrlFilter;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Repositories\EventRepository;
use ServiceBoiler\Prf\Site\Repositories\EventTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\AnnouncementRepository;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\IndexCardsBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\VideoBlockRepository;

class IndexController extends Controller
{

    /**
     * @var AnnouncementRepository
     */
    protected $announcements;
    /**
     * @var EventRepository
     */
    private $events;
    /**
     * @var EventTypeRepository
     */
    private $types;
    private $videoBlocks;
    private $headBannerBlocks;
    private $indexCardsBlocks;

    /**
     * Create a new controller instance.
     *
     * @param AnnouncementRepository $announcements
     * @param EventRepository $events
     * @param EventTypeRepository $types
     */
    public function __construct(
        AnnouncementRepository $announcements,
        EventRepository $events,
        EventTypeRepository $types,
        VideoBlockRepository $videoBlocks,
        IndexCardsBlockRepository $indexCardsBlocks,
        HeadBannerBlockRepository $headBannerBlocks
    )
    {
        $this->announcements = $announcements;
        $this->events = $events;
        $this->types = $types;
        $this->videoBlocks = $videoBlocks;
        $this->indexCardsBlocks = $indexCardsBlocks;
        $this->headBannerBlocks = $headBannerBlocks;
    }

    /**
     * Show application index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $announcements = $this->announcements
            ->applyFilter(new Filters\Announcement\SortDateFilter())
            ->applyFilter(new Filters\Announcement\PublishedFilter())
            ->applyFilter(new Filters\Announcement\LimitThreeFilter())
            ->all(['announcements.*']);
        $events = $this->events
            ->applyFilter(new SortDateFromFilter())
            ->applyFilter(new EventRunnedFilter())
//            ->applyFilter(new SortDateFilter())
//            ->applyFilter(new PublishedFilter())
//            ->applyFilter(new LimitSixFilter())
            ->all(['events.*']);
        $event_types = $this->types
            ->applyFilter(new Filters\EventType\SortFilter())
            ->applyFilter(new Filters\EventType\EventTypeShowFilter())
            ->all(['event_types.*']);
				
			$videoBlocks = $this->videoBlocks
				->applyFilter(new Filters\ShowFilter())
				->applyFilter(new Filters\BySortOrderFilter())
				->all(['video_blocks.*']);	
			$indexCardsBlocks = $this->indexCardsBlocks
				->applyFilter(new Filters\ShowFilter())
				->applyFilter(new Filters\BySortOrderFilter())
				->all(['index_cards_blocks.*']);
				
			$headBannerBlocks = $this->headBannerBlocks
				->applyFilter(new Filters\ShowFilter())
				->applyFilter(new Filters\BySortOrderFilter())
				->applyFilter(new Filters\PathUrlFilter())
				->all(['head_banner_blocks.*']);

                
        return view('site::index_'.config_url('MIRROR_CONFIG'), compact('announcements', 'events', 'event_types','videoBlocks','headBannerBlocks','indexCardsBlocks'));

    }
}