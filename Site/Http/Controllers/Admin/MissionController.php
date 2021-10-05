<?php
namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use ServiceBoiler\Prf\Site\Exports\Excel\MissionExcel;
use ServiceBoiler\Prf\Site\Events\MissionCreateEvent;
use ServiceBoiler\Prf\Site\Events\MissionStatusChangeEvent;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Models\CrmDivision;
use ServiceBoiler\Prf\Site\Models\CrmProject;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\Mission;
use ServiceBoiler\Prf\Site\Models\MissionUser;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\MissionRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\MissionRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\MissionStatusRequest;

class MissionController extends Controller
{
    use AuthorizesRequests;
   
    protected $missions;
    protected $regions;
    protected $types;
    protected $files;
    
    
    public function __construct(MissionRepository $missions, RegionRepository $regions, FileTypeRepository $types, FileRepository $files)
    {
        $this->missions = $missions;
        $this->regions = $regions;
        $this->files = $files;
        $this->types = $types;
    }
    
    public function index(MissionRequest $request)
    {  
        
        $users = User::query()->whereHas('roles', function ($q) {
                                                $q->whereHas('permissions', function ($q) {$q->where('name','admin_missions_create');});
                                })
                    ->whereHas('missions')
                    ->orderBy('name')->get();
        
        $this->missions->trackFilter();
        
        if ($request->has('excel')) {
			(new MissionExcel())->setRepository($this->missions)->render();
		}
        return view('site::admin.mission.index', [
            'users' => $users,
            'repository' => $this->missions,
            'missions'    => $this->missions->paginate($request->input('filter.per_page', config('site.per_page.missions', 100)), ['missions.*'])
        ]);
    } 
    
    public function show(Mission $mission)
    {
        
        $files = $mission->files()->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 11)->orderBy('sort_order')->get();
        
        $statuses = $mission->statuses()->get();
        return  view('site::admin.mission.show',compact('mission','files', 'file_types','statuses'));
    
    
    }
    
    public function create(MissionRequest $request)
    {  
        $divisions = CrmDivision::query()->orderBy('name')->get();
        $projects = CrmProject::query()->orderBy('name')->get();
        $creator = $request->user();
        $users = User::query()->whereHas('roles', function ($q) {
                                                $q->whereHas('permissions', function ($q) {$q->where('name','admin_missions_create');});
                                })
                    ->orderBy('name')->get();
        
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 11)->orderBy('sort_order')->get();
        
        return view('site::admin.mission.create', compact('divisions','projects','regions','users','creator', 'file_types'));
    }
    
    public function edit(Mission $mission)
    {  
        
        $divisions = CrmDivision::query()->orderBy('name')->get();
        $projects = CrmProject::query()->orderBy('name')->get();
        $users = User::query()->whereHas('roles', function ($q) {
                                                $q->whereHas('permissions', function ($q) {$q->where('name','admin_missions_create');});
                                })
                    ->orderBy('name')->get();
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 11)->orderBy('sort_order')->get();
        
        $files = $mission->files()->get();
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        return view('site::admin.mission.edit', compact('mission','divisions','projects','regions','users','files','file_types'));
    }
   
    public function store(MissionRequest $request)
	{
        $request->user()->missionsCreated()
            ->save($mission = $this->missions->create($request->input('mission')));
        $mission->update(['status_id'=>'1']);
        
        if($request->filled('users')){
            $users = (collect($request->input('users')))->map(function ($main, $user_id) {
                
                return new MissionUser([
                    'user_id' => $user_id,
                    'main'=> !empty($main['main']) ? 1 : 0
                ]);
            });
            
            $mission->missionUsers()->saveMany($users);
        }
     
        $this->setFiles($request, $mission);
      //  event(new MissionCreateEvent($mission));
        
        return redirect()->route('admin.missions.show',$mission)->with('success', trans('site::admin.mission.created'));
    }
    
    
    public function update(MissionRequest $request, Mission $mission)
	{
     
        $mission
            ->update($request->except(['_token', '_method', '_create', 'users','status_id'])['mission']);
       
        $mission->missionUsers()->delete();
        
        if($request->filled('users')){
            $users = (collect($request->input('users')))->map(function ($main, $user_id) {
                
                return new MissionUser([
                    'user_id' => $user_id,
                    'main'=> !empty($main['main']) ? 1 : 0
                ]);
            });
            
            $mission->missionUsers()->saveMany($users);
        }
      
        
       $oldFiles = ($this->setFiles($request, $mission))->pluck('id')->toArray();
       
       $file_types = $request->input('file');
       
       if (!is_null($file_types) && is_array($file_types)) {
            foreach ($file_types as $type_id => $values) {
                foreach ($values as $file_id) {
                    if(!in_array($file_id,$oldFiles)){
                            $file=File::query()->findOrFail($file_id);
                            //$text = $text ."\r\n\r\n Добавлен файл" .$file->name;
                           // $count_changes++;
                    }
                }
            }
        } 
        
        return redirect()->route('admin.missions.show',$mission)->with('success', trans('site::admin.mission.updated'));
    }
    

    public function status(MissionStatusRequest $request, Mission $mission)
    {   
    
       // $text="Статус изменен: " .$mission->status->name ." => " .MissionStatus::query()->findOrFail($request->input('status_id'))->name;
        $mission->update(['status_id'=>$request->input('status_id')]);       
        if(!empty($request->input('mission.result'))) {
            $mission->update(['result'=>$request->input('mission.result')]);       
        }
        
        $mission=Mission::find($mission->id);
       
        event(new MissionStatusChangeEvent($mission));
        
        return redirect()->route('admin.missions.show', $mission)->with('success', 'Статус обновлен');
    }
    
    
    public function destroy(Mission $mission)
    {
        $this->authorize('delete', $mission);
        if ($mission->delete()) {
            Session::flash('success', trans('site::admin.mission.deleted'));
            //return redirect()->route('admin.missions.index');
        } else {
            Session::flash('error', trans('site::admin.mission.error_deleted'));
        }
        $json['redirect'] = route('admin.missions.index');

        return response()->json($json);
      //   return redirect()->route('admin.missions.index');
    }
    
    private function getFiles(MissionRequest $request, Mission $mission = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($mission)) {
            $files = $files->merge($mission->files);
        }

        return $files;
    }

    private function setFiles(MissionRequest $request, Mission $mission)
    {
        $old_files=$mission->files;
        $mission->detachFiles();
        
        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $mission->files()->save(File::find($file_id));
                }
            }
        }
       
        return $mission->files;
        //$this->files->deleteLostFiles();
    }
    
}