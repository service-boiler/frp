<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Message\BelongsScFilter;
use ServiceBoiler\Prf\Site\Filters\Message\NotPersonalFilter;
use ServiceBoiler\Prf\Site\Models\Message;
use ServiceBoiler\Prf\Site\Repositories\MessageRepository;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->messages->trackFilter();
        $this->messages->pushTrackFilter(BelongsScFilter::class);
        $this->messages->pushTrackFilter(NotPersonalFilter::class);
       foreach($this->messages->all()->where('readed','0') as $message) {
            $message->update(['readed'=>'1']);
       }
       
       // $this->messages->update(['readed'=>'1']);
        return view('site::message.index', [
            'repository' => $this->messages,
            'messages'   => $this->messages->paginate(config('site.per_page.message', 10), ['messages.*']),
            'user' => Auth()->user(),
            'only_send' => '1'
        ]);
    }

    public function show(Message $message)
    {
        return view('site::message.show', ['message' => $message]);
    }
}