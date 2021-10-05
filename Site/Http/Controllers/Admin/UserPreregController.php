<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use ServiceBoiler\Prf\Site\Filters\FerroliManagerFilter;
use ServiceBoiler\Prf\Site\Filters\OrderSortFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\UserPrereg\FerroliManagerSelectFilter;
use ServiceBoiler\Prf\Site\Filters\UserPrereg\InvitedSelectFilter;
use ServiceBoiler\Prf\Site\Filters\UserPrereg\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\UserPrereg\RegionDistrictFilter;
use ServiceBoiler\Prf\Site\Filters\UserPrereg\RegistredSelectFilter;
use ServiceBoiler\Prf\Site\Filters\UserPrereg\PreregSearchFilter;
use ServiceBoiler\Prf\Site\Support\UserpreregsMountersXlsLoadFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\MailingSendRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\UserpreregsMountersXlsRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\PreregMounterRequest;
use ServiceBoiler\Prf\Site\Mail\Guest\PreregHtmlEmail;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Role;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\UserPrereg;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\TemplateRepository;
use ServiceBoiler\Prf\Site\Repositories\UserPreregRepository;

use Fomvasss\Dadata\Facades\DadataSuggest;


class UserPreregController extends Controller
{

    use AuthorizesRequests;
   
   /**
     * @var RegionRepository
     */
    protected $regions;

    /**
     * @var TemplateRepository
     */
    private $templates;
    /**
     * @var MemberRepository
     */
   
    private $participants;
    
    public function __construct(
        RegionRepository $regions,
        TemplateRepository $templates,
        UserPreregRepository $userPreregs
        
    )
    {
        $this->userPreregs = $userPreregs;
        $this->regions = $regions;
        $this->templates = $templates;
    }

   

    public function mounters(Request $request)
    {   
        $this->userPreregs->trackFilter();
        $this->userPreregs->applyFilter(new FerroliManagerFilter());
        $this->userPreregs->applyFilter(new OrderSortFilter());
        $this->userPreregs->pushTrackFilter(PreregSearchFilter::class);
        $this->userPreregs->pushTrackFilter(InvitedSelectFilter::class);
        $this->userPreregs->pushTrackFilter(RegistredSelectFilter::class);
        $this->userPreregs->pushTrackFilter(RegionFilter::class);
        $this->userPreregs->pushTrackFilter(RegionDistrictFilter::class);
        if(auth()->user()->hasRole('админ') || auth()->user()->hasRole('supervisor') ) {
        $this->userPreregs->pushTrackFilter(FerroliManagerSelectFilter::class);
        }
        
        
        
        
        
        return view('site::admin.user_prereg.mounters', ['repository' => $this->userPreregs,
            'userPreregs' => $this->userPreregs->paginate($request->input('filter.per_page', config('site.per_page.event', 100))), 
            'templates' => $this->templates->all()
            
        ]);
    }
    public function createMounter(PreregMounterRequest $request)
    {   
       
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        
        return view('site::admin.user_prereg.create_mounter', compact('regions'));
    }


    public function store(PreregMounterRequest $request)
    {
       
        $userPrereg = $this->userPreregs->create(array_merge(
            $request->input(['prereg']),
            ['ferroli_manager_id'  => auth()->user()->id]
        ));
        if(!empty($userPrereg->parentUser)) {
            $userPrereg->update(['parent_name'=>$userPrereg->parentUser->name]);
        }
       
        return redirect()->route('ferroli-user.user_prereg.mounters')->with('success', 'Монтажник добавлен');
    }


    public function edit(PreregMounterRequest $request, UserPrereg $prereg)
    {
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        
        return view('site::admin.user_prereg.edit', compact('regions','prereg'));
    }


    public function update(PreregMounterRequest $request, UserPrereg $prereg)
    {
       
        $prereg->update($request->input(['prereg']));

        return redirect()->route('ferroli-user.user_prereg.mounters')->with('success', 'Монтажник обновлен');
    }


    public function inviteMounters(MailingSendRequest $request)
    {   
        
        $preregs=UserPrereg::whereIn('id',$request->recipient)->get();
        
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
       
       foreach($preregs as $prereg)
       {    
            $email = $prereg->email;
            $guid = $prereg->guid;
            $prereg->update(['invited'=>'1']);
            
            Mail::to($email)
                ->send(new PreregHtmlEmail(
                    URL::signedRoute('unsubscribe', compact('email')),
                    route('register_prereg', $guid),
                    $request->input('title'),
                    $request->input('content'),
                    $data
                ));
       
       }
        return redirect()->route('ferroli-user.user_prereg.mounters')->with('success', 'Рассылка отправлена');
    }

	/**
	 * @param Event $event
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
    public function destroy(UserPrereg $userprereg)
    {   //dd($userprereg);
        $lastname = $userprereg->lastname;
        if ($userprereg->delete()) {
            Session::flash('success', 'Запись удалена ' .$lastname);
        } else {
        
            Session::flash('error', trans('site::event.error.deleted'));
        }
        $json['redirect'] = route('ferroli-user.user_prereg.mounters');

        return response()->json($json);
    }
    
  
    
    
  
    
    public function storeUserpreregsMountersXls(UserpreregsMountersXlsRequest $request)
    {

        $inputFileType = ucfirst($request->path->getClientOriginalExtension());
        $filterSubset = new UserpreregsMountersXlsLoadFilter();
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($filterSubset);

        $spreadsheet = $reader->load($request->path->getPathname());

        $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();

        $data = [];

        foreach ($rowIterator as $r => $row) {


            $cellIterator = $row->getCellIterator();

            $name = $lastname = $firstname = $middlename = $company = $email = $phone = $user_id = $city = $region_id = null;
            $role_id = '14';
            foreach ($cellIterator as $c => $cell) {


                switch ($c) {
                    case 'A':
                        $name = (string)trim($cell->getValue());
                        break;
                    case 'B':
                        $company = (string)trim($cell->getValue());
                        break;
                    case 'C':
                        $email = (string)$cell->getValue();
                        break;
                    case 'D':
                        $phone = (string)$cell->getValue();
                        break;
                    case 'E':
                        $value = (string)$cell->getValue();
                        if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                $city='';
                            } else {
                                $value = (string)trim($value);
                                usleep(100000);
                                 $result = DadataSuggest::suggest("address", ["query"=>"$value", "to_bound"=>['value'=>'city'], "from_bound"=>['value'=>'city']]);
                                 
                                
                                 if (!empty($result)) {
                                      if(!empty($result['value'])) {
                                            $city = $result['value'];
                                            $region_id = $result['data']['region_iso_code'];
                                        } else {
                                            $city = $result['0']['value'];
                                            $region_id = $result['0']['data']['region_iso_code'];
                                        }
                                    }
                            }
                        break;
                    case 'F':
                        $value = (string)$cell->getValue();
                        if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                $city='';
                            } else {
                                $value = (string)trim($value);
                                usleep(100000);
                                 $result = DadataSuggest::suggest("address", ["query"=>"$value", "to_bound"=>['value'=>'region'], "from_bound"=>['value'=>'region']]);
                                
                                  if (!empty($result)) {
                                      if(!empty($result['data'])) {
                                            $region_id = $result['data']['region_iso_code'];
                                        } else {
                                            $region_id = $result['0']['data']['region_iso_code'];
                                        }
                                    }
                            }
                        break;
                    case 'G':
                        $value = (string)trim($cell->getValue());
                        
                        if (!empty($value) && mb_strlen($value, 'UTF-8') != 0) {
                      
                                $role_id=Role::where('title',$value)->orWhere('name',$value)->first()->id;
                               
                            }
                        break;
                }
            }
            list($lastName, $firstName, $middleName,) = explode(' ', $name);
            
            if ($name !== false) {
             
                UserPrereg::updateOrCreate(
                        ['lastname' => $lastName,'firstname' => $firstName, 'middlename' =>$middleName],
                        ['parent_name'              => $company, 
                         'email'                => $email, 
                         'phone'                => $phone, 
                         'role_id'              => $role_id,
                         'region_id'              => $region_id,
                         'locality'              => $city,
                         'ferroli_manager_id'   => auth()->user()->id,
                         //'guid'                 => Str::uuid()->toString()
                         ]
                    );
                   
                    
            
                
            }
        }

        return redirect()->route('ferroli-user.user_prereg.mounters')->with('success', 'Монтажники добавлены');
    }

}