<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyProgramRequest;
use ServiceBoiler\Prf\Site\Models\AcademyAnswer;
use ServiceBoiler\Prf\Site\Models\AcademyUserStage;
use ServiceBoiler\Prf\Site\Models\AcademyUserStageQuestion;
use ServiceBoiler\Prf\Site\Models\AcademyPresentation;
use ServiceBoiler\Prf\Site\Models\AcademyPresentationSlide;
use ServiceBoiler\Prf\Site\Models\AcademyProgram;
use ServiceBoiler\Prf\Site\Models\AcademyQuestion;
use ServiceBoiler\Prf\Site\Models\AcademyStage;
use ServiceBoiler\Prf\Site\Models\AcademyTheme;
use ServiceBoiler\Prf\Site\Models\AcademyTest;
use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Models\UserRelation;
use ServiceBoiler\Prf\Site\Repositories\AcademyProgramRepository;


class AcademyProgramController extends Controller
{

    use AuthorizesRequests;

    protected $academyPrograms;
    

    /**
     * Create a new controller instance.
     *
     * @param AcademyProgramRepository $academyPrograms
     */
    public function __construct(AcademyProgramRepository $academyPrograms)
    {
        $this->academyPrograms = $academyPrograms;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = $this->academyPrograms
                        ->applyFilter(new Filters\EnabledFilter())
                        ->all(['academy_programs.*']);
                        
        return view('site::academy_ferroli.program.index', [
            'programs' => $programs
        ]);
    }

    public function show(AcademyProgram $program)
    {   
        $user=Auth::user();
        $stages = $program->stages()->orderBy('sort_order', 'asc')->get();
        $stagesRequired = $program->stages()->where('is_required','1')->get();
        $userStages = AcademyUserStage::where('user_id',$user->getKey())->where('program_id',$program->getKey())->get()->toArray();
        $userCompletedStages = AcademyUserStage::join('academy_stages','academy_user_stages.stage_id','=','academy_stages.id')
            ->where('user_id',$user->getKey())->where('is_required','1')->where('program_id',$program->getKey())->where('completed','1')
            ->select('academy_user_stages.stage_id','academy_user_stages.created_at')->get();
        $userLastCompletedStage = $userCompletedStages->sortByDesc('created_at')->first();
                
        if($userCompletedStages->count() >= $stagesRequired->count()) 
        {
        
        
            if($program->id == '1') {
            $programUserStatus = 'completed';
                 $data = [
                    'id' => config('site.certificate_first_letter', 'R').$userLastCompletedStage->created_at->format('ymd').auth()->user()->getKey(),
                    'type_id' => 2,
                    'name' => $user->name,
                    'is_academy' => 1,
                    'created_at' => $userLastCompletedStage->created_at->format('Y-m-d H:i:s')
                ];
                
                if(!empty($user->acceptedParents->first())) {
                    $data['organization'] =  $user->acceptedParents->first()->name;
                } else {
                
                }
                if (!is_null($data['id']) && ($oldcerts = Certificate::query()->where('type_id', '2')->where('id', $data['id']))->exists()) { 
                         $oldcerts->first()->user()->associate($user)->save();
                         $certificate = $oldcerts->where('id', $data['id'])->first();
                    }
                    else {
                     $certificate =  $user->userCertificates()->create($data);
                    }
            } elseif($program->id == '3') {
            $programUserStatus = 'completed';
                 $data = [
                    'id' => config('site.certificate_sale_first_letter', 'T').$userLastCompletedStage->created_at->format('ymd').auth()->user()->getKey(),
                    'type_id' => 3,
                    'name' => $user->name,
                    'is_academy' => 1,
                    'created_at' => $userLastCompletedStage->created_at->format('Y-m-d H:i:s')
                ];
                
                if(!empty($user->acceptedParents->first())) {
                    $data['organization'] =  $user->acceptedParents->first()->name;
                } else {
                
                }
                if (!is_null($data['id']) && ($oldcerts = Certificate::query()->where('type_id', '3')->where('id', $data['id']))->exists()) { 
                         $oldcerts->first()->user()->associate($user)->save();
                         $certificate = $oldcerts->where('id', $data['id'])->first();
                    }
                    else {
                     $certificate =  $user->userCertificates()->create($data);
                    }
            } elseif ($program->id == '2') { 
                    if((!empty($user->acceptedParents->first()) && ($user->acceptedParents->first()->hasRole('csc') || $user->acceptedParents->first()->hasRole('asc'))) 
                    && ($user->hasRole('service_fl') || $user->hasRole('asc'))) 
                    {
                        $programUserStatus = 'completed';
                        $data = [
                            'id' => config('site.certificate_srv_first_letter', 'S').$userLastCompletedStage->created_at->format('ymd').auth()->user()->getKey(),
                            'type_id' => 1,
                            'name' => $user->name,
                            'is_academy' => 1,
                            'organization' => $user->acceptedParents->first()->name,
                            'created_at' => $userLastCompletedStage->created_at->format('Y-m-d H:i:s')
                        ];
                         
                        if (!is_null($data['id']) && ($oldcerts = Certificate::query()->where('type_id', '1')->where('id', $data['id']))->exists()) { 
                                 $oldcerts->first()->user()->associate($user)->save();
                                 $certificate = $oldcerts->where('id', $data['id'])->first();
                                 $userRelation = UserRelation::where('child_id',$user->id)->where('enabled','1')->where('accepted','1')->orderByDesc('created_at')->first();
                                 if(!empty($userRelation)) {
                                    $lastName = explode(' ',$userRelation->child->name)[0];
                                    $firstName = explode(' ',$userRelation->child->name)[1]; 
                                    
                                    if(empty($userRelation->parent->engineers()->where('email',$userRelation->child->email)->count())){
                                    
                                        if(!empty($userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->count())) {
                                            $userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')
                                                ->update(['fl_user_id'=>$userRelation->child->id]);
                                            $userRelation->child->certificates()
                                                ->update(['engineer_id'=>$userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->orderByDesc('created_at')->first()->id]);
                                            
                                        }
                                    

                                        if(empty($userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->count()) && 
                                            empty($userRelation->parent->engineers()->where('email',$userRelation->child->email)->count())){
                                    
                                                $createdEngineer=$userRelation->parent->engineers()->create(['user_id'=>$userRelation->parent->id,
                                                                                                'fl_user_id'=>$userRelation->child->id, 
                                                                                                'name'=>$userRelation->child->name, 
                                                                                                'email'=>$userRelation->child->email, 
                                                                                                'address'=>$userRelation->child->addresses->first()->region->name .", " .$userRelation->child->addresses->first()->locality, 
                                                                                                'phone'=>$userRelation->child->contacts->first()->phones->first()->number]);
                                                $userRelation->child->certificates()
                                                    ->update(['engineer_id'=>$createdEngineer->id]);
                                                $updateMessage = $createdEngineer->name .' ' .trans('site::messages.created') .' ' .trans('site::messages.success');
                                   
                                        }
                                    } else {
                                        $updatedEngineers=$userRelation->parent->engineers()->where('email',$userRelation->child->email)->orWhere('name',$userRelation->child->name)
                                            ->update(['fl_user_id'=>$userRelation->child->id]);
                                        
                                        $userRelation->child->certificates()
                                            ->update(['engineer_id'=>$userRelation->parent->engineers()->where('email',$userRelation->child->email)->orderByDesc('created_at')->first()->id]);
                                    
                                    }
                                 
                                } 
                                 $certificate ->update([''=>'']);
                            }
                            else {
                             $certificate =  $user->userCertificates()->create($data);
                            }
                    } else {
                    $programUserStatus = 'completed_no_relation';
                    }
            
            } else {
                $programUserStatus = 'Пройдено уроков ' .$userCompletedStages->count() .' из ' .$stagesRequired->count();
                }
        
        
        
        } else {
        $programUserStatus = 'Пройдено уроков ' .$userCompletedStages->count() .' из ' .$stagesRequired->count();
        }
            
      
        
        return view('site::academy_ferroli.program.show', compact('program','stages','user','programUserStatus','certificate'));
    }
    
    public function stage(AcademyProgram $program, AcademyStage $stage)
    {   
         $stages = $program->stages()->orderBy('sort_order', 'asc')->get();
         $user=Auth::user();
         if(!empty($stage->parentStage)) {
             $userParentStage = AcademyUserStage::where('user_id',$user->getKey())->where('program_id',$program->getKey())->where('stage_id',$stage->parentStage->id)->first();
                if(!empty($userParentStage) && $userParentStage->completed){
                $parentStageStatus = 'completed';
                } else {
                    $parentStageStatus = 'required';
                    }
         } else {
            $parentStageStatus = 'norequired';
         }
         
        return view('site::academy_ferroli.program.stage', compact('program','stage','parentStageStatus','userParentStage','user'));
    }
    
    public function presentation(AcademyProgram $program, AcademyStage $stage, AcademyPresentation $presentation)
    {   
        return view('site::academy_ferroli.program.presentation', compact('program','stage','presentation'));
    }
    
    public function slide(AcademyProgram $program, AcademyStage $stage, AcademyPresentation $presentation, $slide_num)
    {   
        return view('site::academy_ferroli.program.slide', compact('program','stage','presentation','slide_num'));
    }
    
    public function test(AcademyProgram $program, AcademyStage $stage, AcademyTest $test)
    {   $user = Auth::user();
        $rand = array_rand($test->questions->toArray(), $test->count_questions);
        return view('site::academy_ferroli.program.test', compact('program','stage','test','rand','user'));
    }
    
    public function sendtest(Request $request, AcademyProgram $program, AcademyStage $stage, AcademyTest $test)
    {   
        $user = Auth::user();
        $userStage = new AcademyUserStage;
        $userStage->user_id = $user->id;
        $userStage->stage_id = $stage->getKey();
        
        $programUserStatus = '';
        
        $userStage->program_id = $program->getKey();
      
        if(AcademyUserStage::where('user_id',$user->id)->where('stage_id',$stage->getKey())->where('program_id',$program->getKey())->exists()) {
            $userStage = AcademyUserStage::where('user_id',$user->id)->where('stage_id',$stage->getKey())->where('program_id',$program->getKey())->first();
            $userStage->userStageQuestions()->delete();
        } else {
            $userStage->save();
        }
        $count_correct = 0;
        if(!empty($request->input('answers'))) {
            foreach($request->input('answers') as $question_id=>$answer_id) {
                $question = AcademyQuestion::find($question_id);
                $answer = AcademyAnswer::find($answer_id);
                $userStageQuestion=AcademyUserStageQuestion::updateOrCreate( [ 'user_stage_id' => $userStage->getKey() , 'question_id' => $question->getKey()]);
                $userStageQuestion->answer_id = $answer->getKey();
                $userStageQuestion->is_correct = $answer->is_correct;
                $userStageQuestion->save();
                if($answer->is_correct) {
                    $count_correct++;
                }
            }
        }
        if($count_correct >= $test->count_correct) {
            $userStage->completed = 1;
            $userStage->save();
            $stageComplete = 1;
            
            
            $stages = $program->stages()->orderBy('sort_order', 'asc')->get();
            $stagesRequired = $program->stages()->where('is_required','1')->get();
            $userStages = AcademyUserStage::where('user_id',$user->getKey())->where('program_id',$program->getKey())->get()->toArray();
            $userCompletedStages = AcademyUserStage::join('academy_stages','academy_user_stages.stage_id','=','academy_stages.id')
            ->where('user_id',$user->getKey())->where('is_required','1')->where('program_id',$program->getKey())->where('completed','1')
            ->select('academy_user_stages.stage_id','academy_user_stages.created_at')->get();
            $userLastCompletedStage = $userCompletedStages->sortByDesc('created_at')->first();
                    if($userCompletedStages->count() >= $stagesRequired->count()) 
                        {
                        
                        
                            if($program->id == '1') {
                            $programUserStatus = 'completed';
                                 $data = [
                                    'id' => config('site.certificate_first_letter', 'R').$userLastCompletedStage->created_at->format('ymd').auth()->user()->getKey(),
                                    'type_id' => 2,
                                    'name' => $user->name,
                                    'is_academy' => 1,
                                    'created_at' => $userLastCompletedStage->created_at->format('Y-m-d H:i:s')
                                ];
                                
                                if(!empty($user->acceptedParents->first())) {
                                    $data['organization'] =  $user->acceptedParents->first()->name;
                                } else {
                                
                                }
                                if (!is_null($data['id']) && ($oldcerts = Certificate::query()->where('type_id', '2')->where('id', $data['id']))->exists()) { 
                                         $oldcerts->first()->user()->associate($user)->save();
                                         $certificate = $oldcerts->where('id', $data['id'])->first();
                                    }
                                    else {
                                     $certificate =  $user->userCertificates()->create($data);
                                    }
                            } elseif($program->id == '3') {
                            $programUserStatus = 'completed';
                                 $data = [
                                    'id' => config('site.certificate_first_letter', 'T').$userLastCompletedStage->created_at->format('ymd').auth()->user()->getKey(),
                                    'type_id' => 3,
                                    'name' => $user->name,
                                    'is_academy' => 1,
                                    'created_at' => $userLastCompletedStage->created_at->format('Y-m-d H:i:s')
                                ];
                                
                                if(!empty($user->acceptedParents->first())) {
                                    $data['organization'] =  $user->acceptedParents->first()->name;
                                } else {
                                
                                }
                                if (!is_null($data['id']) && ($oldcerts = Certificate::query()->where('type_id', '3')->where('id', $data['id']))->exists()) { 
                                         $oldcerts->first()->user()->associate($user)->save();
                                         $certificate = $oldcerts->where('id', $data['id'])->first();
                                    }
                                    else {
                                     $certificate =  $user->userCertificates()->create($data);
                                    }
                            } elseif ($program->id == '2') { 
                                    if((!empty($user->acceptedParents->first()) && ($user->acceptedParents->first()->hasRole('csc') || $user->acceptedParents->first()->hasRole('asc'))) 
                                    && ($user->hasRole('service_fl') || $user->hasRole('asc'))) 
                                    {
                                        $programUserStatus = 'completed';
                                        $data = [
                                            'id' => config('site.certificate_srv_first_letter', 'S').$userLastCompletedStage->created_at->format('ymd').auth()->user()->getKey(),
                                            'type_id' => 1,
                                            'name' => $user->name,
                                            'is_academy' => 1,
                                            'organization' => $user->acceptedParents->first()->name,
                                            'created_at' => $userLastCompletedStage->created_at->format('Y-m-d H:i:s')
                                        ];
                                         
                                        if (!is_null($data['id']) && ($oldcerts = Certificate::query()->where('type_id', '1')->where('id', $data['id']))->exists()) { 
                                                 $oldcerts->first()->user()->associate($user)->save();
                                                 $certificate = $oldcerts->where('id', $data['id'])->first();
                                                 $userRelation = UserRelation::where('child_id',$user->id)->where('enabled','1')->where('accepted','1')->orderByDesc('created_at')->first();
                                                 if(!empty($userRelation)) {
                                                    $lastName = explode(' ',$userRelation->child->name)[0];
                                                    $firstName = explode(' ',$userRelation->child->name)[1]; 
                                                    
                                                    if(empty($userRelation->parent->engineers()->where('email',$userRelation->child->email)->count())){
                                                    
                                                        if(!empty($userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->count())) {
                                                            $userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')
                                                                ->update(['fl_user_id'=>$userRelation->child->id]);
                                                            $userRelation->child->certificates()
                                                                ->update(['engineer_id'=>$userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->orderByDesc('created_at')->first()->id]);
                                                            
                                                        }
                                                    

                                                        if(empty($userRelation->parent->engineers()->where('name','like','%'.$lastName.'%')->where('name','like','%'.$firstName.'%')->count()) && 
                                                            empty($userRelation->parent->engineers()->where('email',$userRelation->child->email)->count())){
                                                    
                                                                $createdEngineer=$userRelation->parent->engineers()->create(['user_id'=>$userRelation->parent->id,
                                                                                                                'fl_user_id'=>$userRelation->child->id, 
                                                                                                                'name'=>$userRelation->child->name, 
                                                                                                                'email'=>$userRelation->child->email, 
                                                                                                                'address'=>$userRelation->child->addresses->first()->region->name .", " .$userRelation->child->addresses->first()->locality, 
                                                                                                                'phone'=>$userRelation->child->contacts->first()->phones->first()->number]);
                                                                $userRelation->child->certificates()
                                                                    ->update(['engineer_id'=>$createdEngineer->id]);
                                                                $updateMessage = $createdEngineer->name .' ' .trans('site::messages.created') .' ' .trans('site::messages.success');
                                                   
                                                        }
                                                    } else {
                                                        $updatedEngineers=$userRelation->parent->engineers()->where('email',$userRelation->child->email)
                                                            ->update(['fl_user_id'=>$userRelation->child->id]);
                                                        
                                                        $userRelation->child->certificates()
                                                            ->update(['engineer_id'=>$userRelation->parent->engineers()->where('email',$userRelation->child->email)->orderByDesc('created_at')->first()->id]);
                                                    
                                                    }
                                                 
                                                } 
                                                 $certificate ->update([''=>'']);
                                            }
                                            else {
                                             $certificate =  $user->userCertificates()->create($data);
                                            }
                                    } else {
                                    $programUserStatus = 'completed_no_relation';
                                    }
                            
                            } else {
                                $programUserStatus = 'Пройдено уроков ' .$userCompletedStages->count() .' из ' .$stagesRequired->count();
                                }
                        
                        
                        
                        } else {
                        $programUserStatus = 'Пройдено уроков ' .$userCompletedStages->count() .' из ' .$stagesRequired->count();
                        }
                        
                        
                        
                        
                        
                        
            
        
        } else { $stageComplete = 0; }
        
        return view('site::academy_ferroli.program.test_result', compact('program','stage','test','userStage','stageComplete','user','programUserStatus'));
    }

	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(AcademyProgramRequest $request)
    {
        //$this->authorize('create', AcademyProgram::class);
        $stages = collect([]);
        $themes = AcademyTheme::query()->get();
        return view('site::academy_ferroli.program.create', compact('themes','stages'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcademyProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcademyProgramRequest $request)
    {
          
        $program = $this->academyPrograms->create(array_merge(
                $request->input(['program']),
                [
                    'enabled'     => $request->filled('program.enabled')
                ]
                ));
        $program->attachStages($request->input('stages'));
        
        return redirect()->route('academy_ferroli.programs.show', $program)->with('success', trans('site::academy.program.created'));
    }

    public function edit(AcademyProgram $program)
    {
        $stages = $program->stages()->orderBy('sort_order', 'asc')->get();
        $themes = AcademyTheme::query()->get();
        
        return view('site::academy_ferroli.program.edit', compact('program','themes','stages'));
    }

    public function update(AcademyProgramRequest $request, AcademyProgram $program)
    {
        $program->update(array_merge(
                $request->input(['program']),
                [
                    'enabled'     => $request->filled('program.enabled')
                ]
                ));
        
        if($request->input('stages')) {
        $program->syncStages($request->input('stages'));
        } else {
           $program->detachStages($program->stages->toArray());     
        }
        
      
        return redirect()->route('academy_ferroli.programs.show', $program)->with('success', trans('site::academy.program.updated'));
    }
    
    public function destroy(AcademyProgram $program)
    {

        if ($program->delete()) {
            return redirect()->route('academy_ferroli.programs.index')->with('success', trans('site::academy_ferroli.program_deleted'));
        } else {
            return redirect()->route('academy_ferroli.programs.show', $program)->with('error', trans('site::academy_ferroli.program_error_deleted'));
        }
    }


}