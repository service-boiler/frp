<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Events\EsbClaimCreateEvent;
use ServiceBoiler\Prf\Site\Events\EsbClaimStatusChangeEvent;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\SortCreatedAtDescFilter;
use ServiceBoiler\Prf\Site\Filters\EsbClaim\EsbClaimPermissionFilter;
use ServiceBoiler\Prf\Site\Filters\EsbClaim\EsbClaimSearchFilter;
use ServiceBoiler\Prf\Site\Http\Requests\EsbClaimRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\EsbClaim;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\EsbClaimRepository;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class EsbClaimController extends Controller
{

	use  AuthorizesRequests, StoreMessages;
	/**
	 * @var EsbClaimRepository
	 */
	protected $esbClaims;

	/**
	 * Create a new controller instance.
	 *
	 * @param EsbClaimRepository $esbClaims
	 */
	public function __construct(EsbClaimRepository $esbClaims)
	{
		$this->middleware('auth');
		$this->esbClaims = $esbClaims;
	}

	/**
	 * Show the shop index page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->esbClaims->trackFilter();
        $this->esbClaims->applyFilter(new EsbClaimPermissionFilter());
        $this->esbClaims->applyFilter(new SortCreatedAtDescFilter());
        $this->esbClaims->pushTrackFilter(EsbClaimSearchFilter::class);

		return view('site::partner_plus_request.index', [
			'esbClaims' => $this->esbClaims->paginate(config('site.per_page.order', 8)),
			'repository' => $this->esbClaims,
		]);
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function create(EsbClaimRequest $request)
	{
        
        $addresses =  $request->user()->addresses()->where('type_id',2)->get();
        $contragents = $request->user()->contragents()->orderBy('name')->get();
        $user = $request->user();
        
        
        
		return view('site::partner_plus_request.create', compact('addresses','contragents','user'));
	}
	public function edit(EsbClaimRequest $request, EsbClaim $esbClaim)
	{
        $user = $esbClaim->partner;
        $addresses =  $user->addresses()->where('type_id',2)->get();
        $contragents = $user->contragents()->orderBy('name')->get();
        
		return view('site::partner_plus_request.edit', compact('esbClaim','addresses','contragents','warehouses','user'));
	}
    
	public function user()
	{
        return view('site::partner_plus_request.user');
	}
	public function createAdmin(EsbClaimRequest $request, User $user)
	{
        
        $addresses =  $user->addresses()->where('type_id',2)->get();
        $contragents = $user->contragents()->orderBy('name')->get();
        
		return view('site::partner_plus_request.create', compact('addresses','contragents','distributors','user'));
	}

	
	public function show(EsbClaim $esbClaim)
	{
		$this->authorize('view', $esbClaim);
        
        $statuses = $esbClaim->statuses()->get();
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', config('site.partner_plus_request_group_file'))->orderBy('sort_order')->get();
        $files = $esbClaim->files()->get();
        $commentBox = new CommentBox($esbClaim);
		return view('site::partner_plus_request.show', compact('esbClaim','file_types','files','statuses','commentBox'));
	}
    
    

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  EsbClaimRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(EsbClaimRequest $request)
	{    
        //$this->authorize('create', EsbClaim::class);
        $esbClaim = $this->esbClaims->create($request->input(['esbClaim']));
        $esbClaim->created_by_id=$request->user()->id;
        $esbClaim->save();
        
        if($request->input('message.text')) {
            $esbClaim->messages()->save($request->user()->outbox()->create($request->message));
        }
       
       event(new EsbClaimCreateEvent($esbClaim));
        
       return redirect()->route('partner-plus-requests.show', $esbClaim)->with('success', trans('site::user.partner_plus_request.created'));
    }

	public function message(MessageRequest $request, EsbClaim $esbClaim)
	{   
        return $this->storeMessage($request, $esbClaim);
	}


	/**
	 * @param EsbClaimRequest $request
	 * @param  EsbClaim $esbClaim
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(EsbClaimRequest $request, EsbClaim $esbClaim)
	{
        
		//$esbClaim->update($request->input('esbClaim'));
        
        if(!empty($request->input('esbClaim'))) {
        $esbClaim->fill($request->input('esbClaim'));
		}
		
        $esbClaim->save();
        
        if(!empty($request->input('status_id'))) {
        
            $esbClaim->status_id=$request->input('status_id');
            $esbClaim->save();
            $receiver_id = $request->user()->getKey();
            $personal = 1;
            $text='Сменен статус на ' . $esbClaim->status->name;
            $esbClaim->messages()->save($request->user()->commentmessage()->create(compact('text', 'receiver_id','personal')));
            
            event(new EsbClaimStatusChangeEvent($esbClaim));
		}
        
        $this->setFiles($request, $esbClaim);
		
        
        return redirect()->route('partner-plus-requests.show', $esbClaim)->with('success', trans('site::user.partner_plus_request.updated'));

	}

    
    private function setFiles(EsbClaimRequest $request, EsbClaim $esbClaim)
    {
        //$esbClaim->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $esbClaim->files()->save(File::find($file_id));
                }
            }
        }
    }
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  EsbClaim $order
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
	public function destroy(EsbClaim $order)
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