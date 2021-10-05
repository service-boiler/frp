<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Message\MessagePerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Message\ScSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Message\SortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Repositories\MessageRepository;
use ServiceBoiler\Prf\Site\Models\Message;

class MessageController extends Controller
{

    protected $messages;

    /**
     * Create a new controller instance.
     *
     * @param MessageRepository $messages
     */
    public function __construct(MessageRepository $messages)
    {
        $this->messages = $messages;
    }

    /**
     * Show the user profile
     *
     * @param MessageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(MessageRequest $request)
    {

        $this->messages->trackFilter();
        $this->messages->applyFilter(new SortFilter());
        $this->messages->pushTrackFilter( ScSearchFilter::class);
        $this->messages->pushTrackFilter(MessagePerPageFilter::class);
        return view('site::admin.message.index', [
            'repository' => $this->messages,
            'messages'      => $this->messages->paginate($request->input('filter.per_page', config('site.per_page.message', 25)), ['messages.*'])
        ]);
    }

    public function show(Message $message)
    {
        return view('site::admin.message.show', compact('message'));
    }
}