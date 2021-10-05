<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use ServiceBoiler\Prf\Site\Filters\FerroliManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\User\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserDoesntHaveUnsubscribeFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\VerifiedFilter;
use ServiceBoiler\Prf\Site\Filters\User\ActiveSelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\MailingSendRequest;
use ServiceBoiler\Prf\Site\Mail\Guest\MailingHtmlEmail;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Unsubscribe;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\TemplateRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;

class MessagingsController extends Controller
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
    public function __construct(UserRepository $users)
    {
        $this->users = $users;

    }

    /**
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $emails = collect([]);
        $this->users->trackFilter();
        $this->users->applyFilter(new FerroliManagerAttachFilter);
        $this->users->applyFilter(new UserNotAdminFilter);
        $this->users->applyFilter(new ActiveSelectFilter);
        $this->users->applyFilter(new UserDoesntHaveUnsubscribeFilter);
        $this->users->pushTrackFilter(RegionFilter::class);
        $this->users->pushTrackFilter(UserRoleFilter::class);
        $this->users->pushTrackFilter(ActiveSelectFilter::class);
        $repository = $this->users;
        $users = $this->users->all();
        /** @var User $user */
      
        $route = route('admin.users.index');

        return view('site::admin.messagings.create', compact('route', 'repository','users'));
    }

    public function store(Request $request)
    {   //dd($request->file('attachment'));
        $manager = Auth()->user();
        
        foreach($request->input('recipient') as $user_id) {
        User::find($user_id)->messages()->save($message = $manager->outbox()->create(['receiver_id'=>$user_id, 'personal'=>0, 'text'=>$request->input('content')]));
        }
       /*
        $data = [];
        $files = $request->file('attachment');
        if (is_array($files) && count($files) > 0) {
            
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

        foreach ($request->input('recipient') as $email) {

            Mail::to($email)
                ->send(new MailingHtmlEmail(
                    URL::signedRoute('unsubscribe', compact('email')),
                    $request->input('title'),
                    $request->input('content'),
                    $data
                ));

        }


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

        return redirect()->back()->with('success', 'Сообщение разослано получателям');
    }
}
