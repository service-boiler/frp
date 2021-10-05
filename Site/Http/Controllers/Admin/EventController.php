<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Filters\Event\ConfirmedSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventShowFerroliBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Event\EventShowLamborghiniBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Event\HasMembersSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\RegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\Event\SortCreatedAtFilter;
use ServiceBoiler\Prf\Site\Filters\Event\StatusSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Event\TypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Member\EventFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\FerroliManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\VerifiedFilter;
use ServiceBoiler\Prf\Site\Filters\User\ActiveSelectFilter;
use ServiceBoiler\Prf\Site\Filters\User\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserDoesntHaveUnsubscribeFilter;
use ServiceBoiler\Prf\Site\Support\ParticipantXlsLoadFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\EventRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ParticipantRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ParticipantXlsRequest;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\Member;
use ServiceBoiler\Prf\Site\Models\Participant;
use ServiceBoiler\Prf\Site\Models\Role;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Unsubscribe;
use ServiceBoiler\Prf\Site\Repositories\EventRepository;
use ServiceBoiler\Prf\Site\Repositories\EventStatusRepository;
use ServiceBoiler\Prf\Site\Repositories\EventTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\MemberRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\ParticipantRepository;
use ServiceBoiler\Prf\Site\Repositories\TemplateRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;



class EventController extends Controller
{

    use AuthorizesRequests, StoreImages;
    /**
     * @var EventRepository
     */
    protected $events;
    /**
     * @var EventTypeRepository
     */
    protected $types;
    /**
     * @var RegionRepository
     */
    protected $regions;

    /**
     * @var EventStatusRepository
     */
    protected $statuses;
    /**
     * @var TemplateRepository
     */
    private $templates;
    /**
     * @var MemberRepository
     */
    private $members;
    
    private $participants;
    private $users;

    /**
     * Create a new controller instance.
     *
     * @param EventRepository $events
     * @param RegionRepository $regions
     * @param EventTypeRepository $types
     * @param EventStatusRepository $statuses
     * @param TemplateRepository $templates
     * @param MemberRepository $members
     */
    public function __construct(
        EventRepository $events,
        RegionRepository $regions,
        EventTypeRepository $types,
        EventStatusRepository $statuses,
        TemplateRepository $templates,
        MemberRepository $members,
        UserRepository $users,
        ParticipantRepository $participants
    )
    {
        $this->events = $events;
        $this->regions = $regions;
        $this->types = $types;
        $this->statuses = $statuses;
        $this->templates = $templates;
        $this->members = $members;
        $this->participants = $participants;
        $this->users = $users;
    }

    /**
     * Show the events index page
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->events->trackFilter();
        $this->events->pushTrackFilter(SearchFilter::class);
        $this->events->pushTrackFilter(EventShowFerroliBoolFilter::class);
        $this->events->pushTrackFilter(EventShowLamborghiniBoolFilter::class);
        $this->events->pushTrackFilter(SortCreatedAtFilter::class);
        $this->events->pushTrackFilter(TypeSelectFilter::class);
        $this->events->pushTrackFilter(StatusSelectFilter::class);
        $this->events->pushTrackFilter(RegionSelectFilter::class);
        $this->events->pushTrackFilter(ConfirmedSelectFilter::class);
        $this->events->pushTrackFilter(EventDateFromFilter::class);
        $this->events->pushTrackFilter(EventDateToFilter::class);
        $this->events->pushTrackFilter(HasMembersSelectFilter::class);
        $this->events->pushTrackFilter(EventPerPageFilter::class);


        return view('site::admin.event.index', [
            'repository' => $this->events,
            'events'     => $this->events->paginate($request->input('filter.per_page', config('site.per_page.event', 10)), ['events.*'])
        ]);
    }

    /**
     * @param EventRequest $request
     * @param Member $member
     * @return \Illuminate\Http\Response
     */
    public function create(EventRequest $request, Member $member)
    {       
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $event_types = $this->types->all();
        $statuses = $this->statuses->all();
        $image = $this->getImage($request);

        return view('site::admin.event.create', compact('regions', 'event_types', 'statuses', 'member', 'image'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EventRequest $request
     * @param Member $member
     * @return \Illuminate\Http\Response
     */

    public function store(EventRequest $request, Member $member)
    {
       
        $event = $this->events->create(array_merge(
            $request->input(['event']),
            [
                'confirmed'        => $request->filled('event.confirmed'),
                'show_ferroli'     => $request->filled('event.show_ferroli'),
                'show_lamborghini' => $request->filled('event.show_lamborghini'),
                'is_webinar' 	   => $request->filled('event.is_webinar')
            ]
        ));
        if ($member->exists) {
            $member->event()->associate($event);
            $member->save();
        }

        return redirect()->route('ferroli-user.events.show', $event)->with('success', trans('site::event.created'));
    }

    /**
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('site::admin.event.show', compact('event'));
    }


    public function attachment()
    {
        $random = mt_rand(10000, 50000);

        return response()->view('site::admin.event.notify.attachment', compact('random'));
    }

    /**
     * @param EventRequest $request
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function edit(EventRequest $request, Event $event)
    {
        $this->authorize('edit', $event);
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $event_types = $this->types->all();
        $statuses = $this->statuses->all();
        $image = $this->getImage($request, $event);

        return view('site::admin.event.edit', compact('event', 'event_types', 'regions', 'statuses', 'image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EventRequest $request
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {

        $event->update(array_merge(
            $request->input(['event']),
            ['confirmed' => $request->filled('event.confirmed')],
            ['show_ferroli' => $request->filled('event.show_ferroli')],
            ['is_webinar' => $request->filled('event.is_webinar')],
            ['show_lamborghini' => $request->filled('event.show_lamborghini')]
        ));

        return redirect()->route('ferroli-user.events.show', $event)->with('success', trans('site::event.updated'));
    }

    /**
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function mailing(Event $event)
    {
        $templates = $this->templates->all();

        $headers = collect([
            trans('site::member.contact'),
            trans('site::member.phone'),
            trans('site::member.name')
        ]);

        $emails = collect([]);
        $emails_user = collect([]);
        $this->members->trackFilter();
        $this->members->applyFilter((new EventFilter())->setEvent($event));
        $repository = $this->members;

        /** @var Member $member */
        foreach ($this->members->all() as $member) {
            $emails->push([
                'email'    => $member->getAttribute('email'),
                'verified' => $member->getAttribute('verified'),
                'extra'    => [
                    'contact' => $member->getAttribute('contact'),
                    'phone'   => $member->getAttribute('phone'),
                    'name'    => $member->getAttribute('name')
                ]
            ]);
        }
        
        
        $this->participants->trackFilter();
        $this->participants->applyFilter((new EventFilter())->setEvent($event));
        $repository = $this->participants;
        
        foreach ($this->participants->all() as $participant) {
            if($participant->getAttribute('email')) {
                $emails->push([
                    'email'    => $participant->getAttribute('email'),
                    'verified' => $participant->getAttribute('verified'),
                    'extra'    => [
                        'contact' => $participant->getAttribute('contact'),
                        'phone'   => $participant->getAttribute('phone'),
                        'name'    => $participant->getAttribute('name')
                    ]
                ]);
            }
        }
        
        $this->users->trackFilter();
        $this->users->applyFilter(new FerroliManagerAttachFilter);
        $this->users->applyFilter(new UserNotAdminFilter);
        $this->users->applyFilter(new ActiveSelectFilter);
        $this->users->applyFilter(new UserDoesntHaveUnsubscribeFilter);
        $this->users->pushTrackFilter(RegionFilter::class);
        $this->users->pushTrackFilter(UserRoleFilter::class);
        $this->users->pushTrackFilter(ActiveSelectFilter::class);
        $repository_users = $this->users;
        $repository = $this->users;
        $duplicates = collect([]);
        $unsubscribers = Unsubscribe::all();
        /** @var User $user */
        foreach ($this->users->all() as $user) {
            if ($duplicates->search($user->getAttribute('email')) === false) {
                $emails_user->push([
                    'email'    => $user->getAttribute('email'),
                    'verified' => $user->getAttribute('verified'),
                    'active' => $user->getAttribute('active'),
                    'extra'    => [
                        'name'    => $user->getAttribute('name'),
                        'address' => '',
                    ],
                ]);
                $duplicates->push($user->getAttribute('email'));
            }

            /** @var Address $address */
            /**убрал отправку на емайлы из фактических адресов foreach ($user->addresses()->get() as $address) {
                if ($address->canSendMail() && $duplicates->search($address->getAttribute('email')) === false) {
                    $emails->push([
                        'email'    => $address->getAttribute('email'),
                        'verified' => false,
                        'extra'    => [
                            'name'    => $user->getAttribute('name'),
                            'address' => $address->getAttribute('name'),
                        ],
                    ]);
                }
                $duplicates->push($address->getAttribute('email'));
            }**/
        }
        
        $route = route('ferroli-user.events.show', $event);
        $route_param = $event;
        $link = route('events.show', $event);
        return view('site::admin.event.mailing', compact('event', 'headers', 'emails', 'emails_user', 'templates', 'route', 'repository', 'repository_users','route_param','link'));
    }

	/**
	 * @param Event $event
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        if ($event->delete()) {
            Session::flash('success', trans('site::event.deleted'));
        } else {
            Session::flash('error', trans('site::event.error.deleted'));
        }
        $json['redirect'] = route('ferroli-user.events.index');

        return response()->json($json);
    }
    
    public function editParticipants(Event $event)
    {
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $members = $event->members;
        $roles = Role::query()->where('display', 1)->where('role_fl', 1)->orderBy('name')->get();
        
        return view('site::admin.event.participants', compact('event', 'countries','members','roles'));
    }
    
    
    public function editParticipant(Event $event, Participant $participant)
    {
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $members = $event->members;
        $roles = Role::query()->where('display', 1)->where('role_fl', 1)->orderBy('name')->get();
        
        return view('site::admin.event.edit_participant', compact('event', 'countries','members','roles','participant'));
    }
    
    public function updateParticipant(ParticipantRequest $request, Event $event, Participant $participant)
    {
        $participant->update($request->input(['participant']));
        
        return redirect()->route('ferroli-user.events.edit_participats', $event)->with('success', trans('site::participant.updated'));
    }
    
    public function storeParticipants(ParticipantRequest $request, Event $event)
    {
        $event->participants()->save(Participant::query()->create($request->input('participant'))->getModel());

        if ($request->input('_create') == 1) {
            $redirect = redirect()->route('ferroli-user.events.edit_participats', $event)->with('success', trans('site::participant.created'));
        } else {
            $redirect = redirect()->route('ferroli-user.events.show', $event)->with('success', trans('site::participant.created'));
        }

        return $redirect;
    }
    
    public function storeParticipantsXls(ParticipantXlsRequest $request, Event $event)
    {

        $inputFileType = ucfirst($request->path->getClientOriginalExtension());
        $filterSubset = new ParticipantXlsLoadFilter();
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($filterSubset);

        $spreadsheet = $reader->load($request->path->getPathname());

        $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();

        $data = [];

        foreach ($rowIterator as $r => $row) {


            $cellIterator = $row->getCellIterator();

            $name = $company = $email = $phone = $role = $user_id = $headposition = false;

            foreach ($cellIterator as $c => $cell) {


                switch ($c) {
                    case 'A':
                        $name = (string)trim($cell->getValue());
                        break;
                    case 'B':
                        $company = (string)trim($cell->getValue());
                        break;
                    case 'C':
                        $role = (string)$cell->getValue();
                        break;
                    case 'D':
                        $phone = (string)$cell->getValue();
                        break;
                    case 'E':
                        $email = (string)$cell->getValue();
                        break;
                }
            }
            if ($name !== false) {
            
                $user = User::where('email',$email)->first();
                
                if($role) {
                    $role_id = Role::where('title',$role)->first()->id;
                    if($role_id) {$headposition = null;}
                } else {
                $role_id = null;
                $headposition = $role;
                }
                if($user) {
                    $user_id = $user->id;
                    $role_id = $user->roles->sortByDesc('id')->first()->id;
                } else {
                    $user_id = null;
                    
                }
            
                DB::table('participants')
                    ->updateOrInsert(
                        ['name' => $name,'event_id' => $event->id],
                        ['company' => $company, 'email' => $email, 'headposition' => $headposition, 
                         'phone' => $phone, 'user_id' => $user_id, 'role_id' => $role_id]
                    );
            }
        }

        return redirect()->route('ferroli-user.events.edit_participats',$event)->with('success', trans('site::event.participants_loaded'));
    }

}