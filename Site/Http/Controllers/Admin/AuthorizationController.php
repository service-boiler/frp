<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\AuthorizationStatusChangeEvent;
use ServiceBoiler\Prf\Site\Events\AuthorizationPreAcceptedEvent;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationRoleSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationRegionFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationUserSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationUserFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationSortFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\FerroliManagersAuthorizationFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AuthorizationRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Models\Authorization;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\AuthorizationStatus;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Repositories\AuthorizationRepository;
use ServiceBoiler\Prf\Site\Repositories\MessageRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class AuthorizationController extends Controller
{

    use  AuthorizesRequests,StoreMessages;

    /**
     * @var AuthorizationRepository
     */
    private $authorizations;

    /**
     * AuthorizationController constructor.
     * @param AuthorizationRepository $authorizations
     */
    public function __construct(AuthorizationRepository $authorizations, MessageRepository $messages)
    {
        $this->authorizations = $authorizations;
		$this->messages = $messages;
    }

    /**
     * @param AuthorizationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(AuthorizationRequest $request)
    {

         $this->authorizations->trackFilter();
         $this->authorizations->applyFilter(new FerroliManagersAuthorizationFilter());
         $this->authorizations->pushTrackFilter(AuthorizationUserSearchFilter::class);
         $this->authorizations->pushTrackFilter(AuthorizationRegionFilter::class);
         $this->authorizations->pushTrackFilter(AuthorizationRoleSelectFilter::class);
         $this->authorizations->pushTrackFilter(AuthorizationUserFilter::class);
         $this->authorizations->pushTrackFilter(AuthorizationPerPageFilter::class);
         $this->authorizations->pushTrackFilter(AuthorizationSortFilter::class);
         $authorizations = $this->authorizations->paginate($request->input('filter.per_page', config('site.per_page.authorization', 100)), ['authorizations.*']);
        $repository = $this->authorizations;

        return view('site::admin.authorization.index', compact('authorizations', 'repository'));
    }

    /**
     * @param Authorization $authorization
     * @return \Illuminate\Http\Response
     */
    public function show(Authorization $authorization)
    {
        
        $this->authorize('view', $authorization);
        $statuses = AuthorizationStatus::query()->where('id', '!=', $authorization->getAttribute('status_id'))->get();
        $authorization_accepts = $authorization->user->authorization_accepts()->get();
        $authorization_roles = AuthorizationRole::query()->get();
        $authorization_types = AuthorizationType::query()->where('enabled', 1)->get();
        $commentBox = new CommentBox($authorization);
        
        return view('site::admin.authorization.show', compact(
            'authorization',
            'statuses',
            'authorization_accepts',
            'authorization_roles',
            'authorization_types',
			'commentBox'
        ));
    }

    public function update(AuthorizationRequest $request, Authorization $authorization)
    {   
        $this->authorize('update', $authorization);
        $authorization->update($request->input(['authorization']));
        
        $text="";
        
        $authorization->makeAccepts();
        if(!empty($request->input(['authorization'])['pre_accepted'])) {
        event(new AuthorizationPreAcceptedEvent($authorization));
        $text='Заявка на авторизацию согласована менеджером';
        }
        if(!empty($request->input(['authorization'])['status_id'])) {
            event(new AuthorizationStatusChangeEvent($authorization));
            $text='Сменен статус на ' . $authorization->status->name;
        }
        
        $receiver_id = $request->user()->getKey();
        $authorization->messages()->save($request->user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        return redirect()->back()->with('success', trans('site::authorization.updated'));
    }

    /**
     * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
     * @param \ServiceBoiler\Prf\Site\Models\Authorization $authorization
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(MessageRequest $request, Authorization $authorization)
    {
        return $this->storeMessage($request, $authorization);
    }
}