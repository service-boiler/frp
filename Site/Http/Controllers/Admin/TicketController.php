<?php
namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use ServiceBoiler\Prf\Site\Exports\Excel\TicketExcel;
use ServiceBoiler\Prf\Site\Events\TicketCreateEvent;
use ServiceBoiler\Prf\Site\Events\TicketStatusChangeEvent;
use ServiceBoiler\Prf\Site\Models\Ticket;
use ServiceBoiler\Prf\Site\Models\TicketStatus;
use ServiceBoiler\Prf\Site\Models\TicketTheme;
use ServiceBoiler\Prf\Site\Models\TicketType;
use ServiceBoiler\Prf\Site\Models\TicketReceiverGroup;
use ServiceBoiler\Prf\Site\Repositories\CountryRepository;
use ServiceBoiler\Prf\Site\Repositories\MessageRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\TicketRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\TicketRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\TicketStatusRequest ;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Filters\ByNameSortFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketSortFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserChiefsFerroliFilter;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class TicketController extends Controller
{
    use AuthorizesRequests, StoreMessages;
   
    protected $tickets;
	protected $messages;
	protected $users;
    protected $regions;
    private $countries;
    
    public function __construct(CountryRepository $countries, RegionRepository $regions,TicketRepository $tickets,UserRepository $users, MessageRepository $messages)
    {
        $this->tickets = $tickets;
        $this->users = $users;
        $this->messages = $messages;
        $this->countries = $countries;
        $this->regions = $regions;
    }
    
    public function index(TicketRequest $request)
    {  
        $this->tickets->trackFilter();
        $this->tickets->applyFilter(new TicketSortFilter());
        if ($request->has('excel')) {
			(new TicketExcel())->setRepository($this->tickets)->render();
		}
        return view('site::admin.ticket.index', [
            'repository' => $this->tickets,
            'tickets'    => $this->tickets->paginate($request->input('filter.per_page', config('site.per_page.tickets', 100)), ['tickets.*'])
        ]);
    } 
    
    public function show(Ticket $ticket)
    {
        $commentBox = new CommentBox($ticket);
        $statuses = $ticket->statuses()->get();
        if($ticket->status_id == 1 && $ticket->receiver_id == auth()->user()->id){
            $ticket->update(['status_id'=>'2']);
            $receiver_id = auth()->user()->getKey();
            $ticket->messages()->save(auth()->user()->outbox()->create(['text'=>'Статус: Прочитано', 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        }
        
        return  view('site::admin.ticket.show',compact('ticket','commentBox','statuses'));
    
    
    }
    
    public function create(TicketRequest $request)
    {  
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $request_params = array();
        $request_params['consumer_phone']=$request->get('callerid');
        $request_params['receiver_id']='909';
        $groups=TicketReceiverGroup::get();
        $this->users->applyFilter(new UserChiefsFerroliFilter);
        $this->users->applyFilter(new ByNameSortFilter);
        $users = $this->users->all();
        $themes=TicketTheme::get();
        $types=TicketType::get();
       
        return view('site::admin.ticket.create', compact('themes','types','request_params','users','groups','regions'));
    }
   
    public function store(TicketRequest $request)
	{
        $request->user()->ticketsCreated()->save($ticket = $this->tickets->create($request->input('ticket')));
        $ticket->update(['status_id'=>'1']);
		event(new TicketCreateEvent($ticket));
        return redirect()->route('admin.tickets.index')->with('success', trans('site::ticket.created'));
    }
     
    public function status(TicketStatusRequest $request, Ticket $ticket)
    {   
        if(in_array($request->input('status_id'),['4','5'])) {
            $ticket->closed_at=Carbon::now();
            $ticket->save();
        }
        
        
        $text="Статус изменен " .$ticket->status->name ." => " .TicketStatus::query()->findOrFail($request->input('status_id'))->name;
           $ticket->update(['status_id'=>$request->input('status_id')]);
       
       
       $receiver_id = $request->user()->getKey();
        if(!empty($request->input('message.text'))){
        $text = $text."\r\n\r\n".$request->input('message.text');
        }
        
        $ticket->messages()->save($request->user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        
        $ticket=Ticket::find($ticket->id);
       
        event(new TicketStatusChangeEvent($ticket));
        
        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Статус обновлен');
    }
    
    
     public function message(MessageRequest $request, Ticket $ticket)
    {   
        //event(new TicketMessageEvent($ticket));
        return $this->storeMessage($request, $ticket);
    }
    
}