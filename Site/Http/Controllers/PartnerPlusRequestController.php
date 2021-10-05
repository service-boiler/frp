<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Events\PartnerPlusRequestCreateEvent;
use ServiceBoiler\Prf\Site\Events\PartnerPlusRequestStatusChangeEvent;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\SortCreatedAtDescFilter;
use ServiceBoiler\Prf\Site\Filters\PartnerPlusRequest\PartnerPlusRequestPermissionFilter;
use ServiceBoiler\Prf\Site\Filters\PartnerPlusRequest\PartnerPlusRequestSearchFilter;
use ServiceBoiler\Prf\Site\Filters\OrderDateFilter;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\PartnerPlusRequestRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\PartnerPlusRequest;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\PartnerPlusRequestRepository;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class PartnerPlusRequestController extends Controller
{

	use  AuthorizesRequests, StoreMessages;
	/**
	 * @var PartnerPlusRequestRepository
	 */
	protected $partnerPlusRequests;

	/**
	 * Create a new controller instance.
	 *
	 * @param PartnerPlusRequestRepository $partnerPlusRequests
	 */
	public function __construct(PartnerPlusRequestRepository $partnerPlusRequests)
	{
		$this->middleware('auth');
		$this->partnerPlusRequests = $partnerPlusRequests;
	}

	/**
	 * Show the shop index page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->partnerPlusRequests->trackFilter();
        $this->partnerPlusRequests->applyFilter(new PartnerPlusRequestPermissionFilter());
        $this->partnerPlusRequests->applyFilter(new SortCreatedAtDescFilter());
        $this->partnerPlusRequests->pushTrackFilter(PartnerPlusRequestSearchFilter::class);

		return view('site::partner_plus_request.index', [
			'partnerPlusRequests' => $this->partnerPlusRequests->paginate(config('site.per_page.order', 8)),
			'repository' => $this->partnerPlusRequests,
		]);
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function create(PartnerPlusRequestRequest $request)
	{
        
        $addresses =  $request->user()->addresses()->where('type_id',2)->get();
        $contragents = $request->user()->contragents()->orderBy('name')->get();
        $user = $request->user();
        
        
        
		return view('site::partner_plus_request.create', compact('addresses','contragents','user'));
	}
	public function edit(PartnerPlusRequestRequest $request, PartnerPlusRequest $partnerPlusRequest)
	{
        $user = $partnerPlusRequest->partner;
        $addresses =  $user->addresses()->where('type_id',2)->get();
        $contragents = $user->contragents()->orderBy('name')->get();
        
		return view('site::partner_plus_request.edit', compact('partnerPlusRequest','addresses','contragents','warehouses','user'));
	}
    
	public function user()
	{
        return view('site::partner_plus_request.user');
	}
	public function createAdmin(PartnerPlusRequestRequest $request, User $user)
	{
        
        $addresses =  $user->addresses()->where('type_id',2)->get();
        $contragents = $user->contragents()->orderBy('name')->get();
        
		return view('site::partner_plus_request.create', compact('addresses','contragents','distributors','user'));
	}

	
	public function show(PartnerPlusRequest $partnerPlusRequest)
	{
		$this->authorize('view', $partnerPlusRequest);
        
        $statuses = $partnerPlusRequest->statuses()->get();
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', config('site.partner_plus_request_group_file'))->orderBy('sort_order')->get();
        $files = $partnerPlusRequest->files()->get();
        $commentBox = new CommentBox($partnerPlusRequest);
		return view('site::partner_plus_request.show', compact('partnerPlusRequest','file_types','files','statuses','commentBox'));
	}
    
    

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  PartnerPlusRequestRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(PartnerPlusRequestRequest $request)
	{    
        //$this->authorize('create', PartnerPlusRequest::class);
        $partnerPlusRequest = $this->partnerPlusRequests->create($request->input(['partnerPlusRequest']));
        $partnerPlusRequest->created_by_id=$request->user()->id;
        $partnerPlusRequest->save();
        
        if($request->input('message.text')) {
            $partnerPlusRequest->messages()->save($request->user()->outbox()->create($request->message));
        }
       
       event(new PartnerPlusRequestCreateEvent($partnerPlusRequest));
        
       return redirect()->route('partner-plus-requests.show', $partnerPlusRequest)->with('success', trans('site::user.partner_plus_request.created'));
    }

	public function message(MessageRequest $request, PartnerPlusRequest $partnerPlusRequest)
	{   
        return $this->storeMessage($request, $partnerPlusRequest);
	}


	/**
	 * @param PartnerPlusRequestRequest $request
	 * @param  PartnerPlusRequest $partnerPlusRequest
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(PartnerPlusRequestRequest $request, PartnerPlusRequest $partnerPlusRequest)
	{
        
		//$partnerPlusRequest->update($request->input('partnerPlusRequest'));
        
        if(!empty($request->input('partnerPlusRequest'))) {
        $partnerPlusRequest->fill($request->input('partnerPlusRequest'));
		}
		
        $partnerPlusRequest->save();
        
        if(!empty($request->input('status_id'))) {
        
            $partnerPlusRequest->status_id=$request->input('status_id');
            $partnerPlusRequest->save();
            $receiver_id = $request->user()->getKey();
            $personal = 1;
            $text='Сменен статус на ' . $partnerPlusRequest->status->name;
            $partnerPlusRequest->messages()->save($request->user()->commentmessage()->create(compact('text', 'receiver_id','personal')));
            
            event(new PartnerPlusRequestStatusChangeEvent($partnerPlusRequest));
		}
        
        $this->setFiles($request, $partnerPlusRequest);
		
        
        return redirect()->route('partner-plus-requests.show', $partnerPlusRequest)->with('success', trans('site::user.partner_plus_request.updated'));

	}

    
    private function setFiles(PartnerPlusRequestRequest $request, PartnerPlusRequest $partnerPlusRequest)
    {
        //$partnerPlusRequest->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $partnerPlusRequest->files()->save(File::find($file_id));
                }
            }
        }
    }
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  PartnerPlusRequest $order
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
	public function destroy(PartnerPlusRequest $order)
	{

		$this->authorize('delete', $order);

		if ($order->delete()) {
			$redirect = route('orders.index');
		} else {
			$redirect = route('orders.show', $order);
		}
		$json['redirect'] = $redirect;

		return response()->json($json);

	}

}