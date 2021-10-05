<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Filters\Announcement\AnnouncementDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Announcement\AnnouncementDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Announcement\AnnouncementPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Announcement\AnnouncementShowFerroliBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Announcement\AnnouncementShowLamborghiniBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Announcement\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\Announcement\SortCreatedAtFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AnnouncementRequest;
use ServiceBoiler\Prf\Site\Models\Announcement;
use ServiceBoiler\Prf\Site\Repositories\AnnouncementRepository;

class AnnouncementController extends Controller
{
    use AuthorizesRequests, StoreImages;
    /**
     * @var AnnouncementRepository
     */
    protected $announcements;

    /**
     * Create a new controller instance.
     *
     * @param AnnouncementRepository $announcements
     */
    public function __construct(AnnouncementRepository $announcements)
    {
        $this->announcements = $announcements;
    }

    /**
     * Show the user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->announcements
            ->trackFilter()
            ->applyFilter(new SortCreatedAtFilter())
            ->pushTrackFilter(SearchFilter::class)
            ->pushTrackFilter(AnnouncementShowFerroliBoolFilter::class)
            ->pushTrackFilter(AnnouncementShowLamborghiniBoolFilter::class)
            ->pushTrackFilter(AnnouncementDateFromFilter::class)
            ->pushTrackFilter(AnnouncementDateToFilter::class)
            ->pushTrackFilter(AnnouncementPerPageFilter::class);

        return view('site::admin.announcement.index', [
            'repository'    => $this->announcements,
            'announcements' => $this->announcements->paginate($request->input('filter.per_page', config('site.per_page.announcement', 10)), ['announcements.*'])
        ]);
    }

    /**
     * @param Announcement $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        return view('site::admin.announcement.show', compact('announcement'));
    }

    /**
     * @param AnnouncementRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(AnnouncementRequest $request)
    {
        $image = $this->getImage($request);

        return view('site::admin.announcement.create', compact('image'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AnnouncementRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnnouncementRequest $request)
    {

        $announcement = $this->announcements->create(array_merge(
            $request->input(['announcement']),
            [
                'show_ferroli'     => $request->filled('announcement.show_ferroli'),
                'show_lamborghini' => $request->filled('announcement.show_lamborghini')
            ]
        ));

        return redirect()->route('admin.announcements.show', $announcement)->with('success', trans('site::announcement.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AnnouncementRequest $request
     * @param Announcement $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(AnnouncementRequest $request, Announcement $announcement)
    {

        $image = $this->getImage($request, $announcement);

        return view('site::admin.announcement.edit', compact('announcement', 'image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AnnouncementRequest $request
     * @param Announcement $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update(array_merge(
            $request->input(['announcement']),
            [
                'show_ferroli'     => $request->filled('announcement.show_ferroli'),
                'show_lamborghini' => $request->filled('announcement.show_lamborghini')
            ]
        ));

        return redirect()->route('admin.announcements.show', $announcement)->with('success', trans('site::announcement.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Announcement $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {

        if ($announcement->delete()) {
            $redirect = route('admin.announcements.index');
        } else {
            $redirect = route('admin.announcements.show', $announcement);
        }
        $json['redirect'] = $redirect;

        return response()->json($json);

    }

}