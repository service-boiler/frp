<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Rbac\Models\Role;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\AuthorizationCreateEvent;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationSortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\AuthorizationRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Models\Authorization;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Repositories\AuthorizationRepository;

class AuthorizationController extends Controller
{

    use AuthorizesRequests, StoreMessages;
    /**
     * @var AuthorizationRepository
     */
    private $authorizations;

    /**
     * AuthorizationController constructor.
     * @param AuthorizationRepository $authorizations
     */
    public function __construct(AuthorizationRepository $authorizations)
    {
        $this->authorizations = $authorizations;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authorization_roles = AuthorizationRole::query()->get();
        $this->authorizations->applyFilter(new BelongsUserFilter());
        $this->authorizations->applyFilter(new AuthorizationSortFilter());
		$authorizations = $this->authorizations->all();
        $authorization_accepts = $request->user()->authorization_accepts()->get();
        $authorization_types = AuthorizationType::query()->where('enabled', 1)->get();

        return view('site::authorization.index', compact(
            'authorization_roles',
            'authorizations',
            'authorization_accepts',
            'authorization_types'
        ));
    }


    /**
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function create(Role $role)
    {
        $this->authorize('authorization', $role);
        $authorization_types = AuthorizationType::query()->enabled()->orderBy('brand_id')->orderBy('name')->get();
        $addresses = Auth::user()->addresses()->where('type_id', $role->authorization_role->address_type_id)->get();
        $address_type = $role->authorization_role->address_type;
        $authorization_accepts = Auth::user()->authorization_accepts()->where('role_id', $role->id)->pluck('type_id');

        return view('site::authorization.create', compact(
            'role',
            'authorization_types',
            'addresses',
            'address_type',
            'authorization_accepts'
        ));
    }

	public function show(Authorization $authorization)
    {
        $authorization_accepts = $authorization->user->authorization_accepts()->get();
        $authorization_roles = AuthorizationRole::query()->get();
        $authorization_types = AuthorizationType::query()->where('enabled', 1)->get();

        return view('site::authorization.show', compact(
            'authorization',
            'authorization_accepts',
            'authorization_roles',
            'authorization_types'
        ));
    }
	
    /**
     * @param  AuthorizationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorizationRequest $request)
    {

        $authorization = $request->user()->authorizations()->create($request->input('authorization'));
        $authorization->attachTypes($request->input('authorization_types', []));
        
        event(new AuthorizationCreateEvent($authorization));

        return redirect()->route('authorizations.index')->with('message', trans('site::authorization.created'));

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