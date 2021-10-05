<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Models\TicketTheme;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\TicketThemeRequest;
use ServiceBoiler\Prf\Site\Repositories\TicketThemeRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use ServiceBoiler\Prf\Site\Filters\ByNameSortFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserChiefsFerroliFilter;

class TicketThemeController extends Controller
{

    protected $ticketThemes;
	protected $users;

    /**
     * Create a new controller instance.
     *
     * @param TicketThemeRepository $ticketThemes
     */
    public function __construct(TicketThemeRepository $ticketThemes,UserRepository $users)
    {
        $this->ticketThemes = $ticketThemes;
        $this->users = $users;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->ticketThemes->trackFilter();
        

        return view('site::admin.ticket_theme.index', [
            'repository' => $this->ticketThemes,
            'ticketThemes'  => $this->ticketThemes->paginate(config('site.per_page.ticket_theme', 100), ['ticket_themes.*'])
        ]);
    }

   public function create(TicketThemeRequest $request)
    {     
        
        $this->users->applyFilter(new UserChiefsFerroliFilter);
        $this->users->applyFilter(new ByNameSortFilter);
        $users = $this->users->all();
        
        return view('site::admin.ticket_theme.create',compact('users'));
    }
   
   
   public function store(TicketThemeRequest $request)
    {
       
        $ticketTheme = $this->ticketThemes->create( array_merge($request->except(['_token', '_method', '_create', 'ticketTheme.for_manager','ticketTheme.for_feedback'])['ticketTheme'],
                                                            ['for_manager' => $request->filled('ticketTheme.for_manager')],
                                                            ['for_feedback' => $request->filled('ticketTheme.for_feedback')]));
        

        if($request->input('_newticket')== 1) {
            $redirect = redirect()->route('admin.tickets.create')->with('theme_id', $ticketTheme->id);
        } else {
            $redirect = redirect()->route('admin.ticket-themes.index')->with('success', trans('site::admin.ticket_theme.created'));
        }
        return $redirect;
    }
    
  /**
     * @param TicketTheme $ticketTheme
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketTheme $ticketTheme)
    {   
        
        $this->users->applyFilter(new UserChiefsFerroliFilter);
        $this->users->applyFilter(new ByNameSortFilter);
        $users = $this->users->all();  
        return view('site::admin.ticket_theme.edit', compact('ticketTheme','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TicketThemeRequest $request
     * @param  TicketTheme $ticketTheme
     * @return \Illuminate\Http\Response
     */
    public function update(TicketThemeRequest $request, TicketTheme $ticketTheme)
    {
       
        $ticketTheme->update(array_merge($request->except(['_token', '_method', '_create', 'ticketTheme.for_manager','ticketTheme.for_feedback'])['ticketTheme'],
                                                            ['for_manager' => $request->filled('ticketTheme.for_manager')],
                                                            ['for_feedback' => $request->filled('ticketTheme.for_feedback')]));
        
        return redirect()->route('admin.ticket-themes.index')->with('success', trans('site::ticket.theme.updated'));;
    }
    
      public function destroy(ticketTheme $ticketTheme)
    {

        if ($ticketTheme->delete()) {
            return redirect()->route('admin.ticketThemes.index')->with('success', trans('site::admin.ticket_theme.deleted'));
        } else {
            return redirect()->route('admin.ticketThemes.show', $ticketTheme)->with('error', trans('site::admin.ticket_theme.error_deleted'));
        }
    }

 
}