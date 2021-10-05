<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Contact\ContactPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Contact\ContactSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Contact\ContactTypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Contact\ContactUserSelectFilter;
use ServiceBoiler\Prf\Site\Repositories\ContactRepository;
use ServiceBoiler\Prf\Site\Models\Contact;

class ContactController extends Controller
{

    protected $contacts;

    /**
     * Create a new controller instance.
     *
     * @param ContactRepository $contacts
     */
    public function __construct(ContactRepository $contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * Show the user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->contacts->trackFilter();
        $this->contacts->pushTrackFilter(ContactSearchFilter::class);
        $this->contacts->pushTrackFilter(ContactTypeSelectFilter::class);
        $this->contacts->pushTrackFilter(ContactUserSelectFilter::class);
        $this->contacts->pushTrackFilter(ContactPerPageFilter::class);
        return view('site::admin.contact.index', [
            'repository' => $this->contacts,
            'contacts'      => $this->contacts->paginate($request->input('filter.per_page', config('site.per_page.contact', 10)), ['contacts.*'])
        ]);
    }

    public function show(Contact $contact)
    {
        return view('site::admin.contact.show', compact('contact'));
    }
}