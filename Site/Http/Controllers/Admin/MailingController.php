<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use ServiceBoiler\Prf\Site\Filters\FerroliManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\User\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserDoesntHaveUnsubscribeFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserVerifiedFilter;
use ServiceBoiler\Prf\Site\Filters\User\ActiveSelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\MailingSendRequest;
use ServiceBoiler\Prf\Site\Mail\Guest\MailingHtmlEmail;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Unsubscribe;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\TemplateRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;

class MailingController extends Controller
{

    private $users;
    /**
     * @var TemplateRepository
     */
    private $templates;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $users
     * @param TemplateRepository $templates
     */
    public function __construct(UserRepository $users, TemplateRepository $templates)
    {
        $this->users = $users;

        $this->templates = $templates;
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $templates = $this->templates->all();

        $headers = collect([
            trans('site::user.name'),
            trans('site::address.full'),
        ]);

        $emails = collect([]);
        $this->users->trackFilter();
        $this->users->applyFilter(new FerroliManagerAttachFilter);
        $this->users->applyFilter(new UserNotAdminFilter);
        $this->users->applyFilter(new UserVerifiedFilter);
        $this->users->applyFilter(new ActiveSelectFilter);
        $this->users->applyFilter(new UserDoesntHaveUnsubscribeFilter);
        $this->users->pushTrackFilter(RegionFilter::class);
        $this->users->pushTrackFilter(UserRoleFilter::class);
        $this->users->pushTrackFilter(ActiveSelectFilter::class);
        $repository = $this->users;
        $duplicates = collect([]);
        $unsubscribers = Unsubscribe::all();
        /** @var User $user */
        foreach ($this->users->all() as $user) {
            if ($duplicates->search($user->getAttribute('email')) === false) {
                $emails->push([
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
        $route = route('admin.users.index');

        return view('site::admin.mailing.create', compact('headers', 'emails', 'templates', 'route', 'repository'));
    }

    public function store(MailingSendRequest $request)
    {

        $data = [];
        $files = $request->file('attachment');
        if (is_array($files) && count($files) > 0) {
            /** @var UploadedFile $file */
            foreach ($files as $file) {
                $data[] = [
                    'file'    => $file->getRealPath(),
                    'options' => [
                        'as'   => $file->getClientOriginalName(),
                        'mime' => $file->getMimeType(),
                    ],

                ];
            }
        }
        $errors = array();
        foreach ($request->input('recipient') as $email) {
        try {
            Mail::to($email)
                ->send(new MailingHtmlEmail(
                    URL::signedRoute('unsubscribe', compact('email')),
                    $request->input('title'),
                    $request->input('content'),
                    $data
                ));
        } catch (\Exception $e) {
            $errors[] = $email;
        } 
        
        }
            
/*
	     Mail::to(env('MAIL_COPY_ADDRESS'))
                ->send(new MailingHtmlEmail(
                    URL::signedRoute('unsubscribe', compact('email')),
                    $request->input('title'),
                    $request->input('content'),
                    $data
                ));

             Mail::to(env('MAIL_DIRECTOR_ADDRESS'))
                ->send(new MailingHtmlEmail(
                    URL::signedRoute('unsubscribe', compact('email')),
                    $request->input('title'),
                    $request->input('content'),
                    $data
                ));

        
  */      
        return redirect()->back()->with('success', trans('site::mailing.created'));
        //return redirect()->back()->with('success', trans('site::mailing.created'))->with('error', "не отправлено адресатам: " .implode(",",$errors));
    }
}
