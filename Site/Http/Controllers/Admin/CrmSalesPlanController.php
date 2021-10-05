<?php
namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use ServiceBoiler\Prf\Site\Exports\Excel\crmSalesPlanExcel;
use ServiceBoiler\Prf\Site\Filters\FerroliManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionHasDistrsFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortNotifiEmailFilter;
use ServiceBoiler\Prf\Site\Filters\User\IsDistrsFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserNotAdminFilter;
use ServiceBoiler\Prf\Site\Models\CrmDivision;
use ServiceBoiler\Prf\Site\Models\CrmProject;
use ServiceBoiler\Prf\Site\Models\CrmSalesPlan;
use ServiceBoiler\Prf\Site\Models\CrmSalesPlanLog;
use ServiceBoiler\Prf\Site\Models\CrmSalesPredict;
use ServiceBoiler\Prf\Site\Models\PeriodType;
use ServiceBoiler\Prf\Site\Models\RegionBizDistrict;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\СrmSalesPlanRequest;

class CrmSalesPlanController extends Controller
{
    use AuthorizesRequests;
   
    protected $regions;
    protected $users;

    
    public function __construct(RegionRepository $regions, UserRepository $users)
    {
        $this->regions = $regions;
        $this->users = $users;
    }
    
    public function index(СrmSalesPlanRequest $request)
    {

       // $bizRegion=RegionBizDistrict::find(1);
       // $users=$bizRegion->users();

        $users = $this->users->trackFilter();
        $this->users->applyFilter(new UserNotAdminFilter);
        $this->users->applyFilter(new FerroliManagerAttachFilter);
        $this->users->applyFilter(new IsDistrsFilter);

        $regions = $this->regions->trackFilter();
        $this->regions->applyFilter(new RegionManagerAttachFilter());
        $this->regions->applyFilter(new RegionHasDistrsFilter());
        $this->regions->applyFilter(new SortNotifiEmailFilter());

        $bizRegions=RegionBizDistrict::wherehas('regions', function ($q) use ($regions) {$q->whereIn('regions.id',$regions->all()->pluck('id'));})
            ->orWhereHas('manualUsers', function ($q) use($users) {$q->whereIn('users.id',$users->all()->pluck('id'));})->get();


        $ferroliManagers=User::whereHas('ferroliManagerRegions', function ($q) { $q->where('country_id','643');});
        $cur_month=(int) Carbon::now()->format('m');

        $logs=CrmSalesPlanLog::orderByDesc('created_at')->limit(1000)->get();

        if(!$year=$request->year)
        {$year=Carbon::now()->format('Y');}
     //   dd($users->all()->first()->id,$users->all()->first()->contragents->first()->id,
     //       $users->all()->first()->crmSalesPlans,$users->all()->first()->contragents->first()->crmSalesPlans);

        if ($request->has('excel')) {
			(new crmSalesPlanExcel())->setRepository($this->crmSalesPlans)->render();
		}

        return view('site::admin.crm_sales_plan.index', [
            'repository' => $this->users,
            'users' => $users,
            'regions' => $regions,
            'ferroliManagers' => $ferroliManagers,
            'bizRegions'=>$bizRegions,
            'year'=>$year,
            'logs'=>$logs,
            'cur_month'=>$cur_month
        ]);
    } 
    
    public function show(crmSalesPlan $crmSalesPlan)
    {
        
        $files = $crmSalesPlan->files()->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 11)->orderBy('sort_order')->get();
        
        $statuses = $crmSalesPlan->statuses()->get();
        return  view('site::admin.crmSalesPlan.show',compact('crmSalesPlan','files', 'file_types','statuses'));
    
    
    }
    
    public function create(crmSalesPlanRequest $request)
    {  
        $divisions = CrmDivision::query()->orderBy('name')->get();
        $projects = CrmProject::query()->orderBy('name')->get();
        $creator = $request->user();
        $users = User::query()->whereHas('roles', function ($q) {
                                                $q->whereHas('percrmSalesPlans', function ($q) {$q->where('name','admin_crmSalesPlans_create');});
                                })
                    ->orderBy('name')->get();
        
        $events = Event::where('date_from','>',Carbon::now()->subDays(7))->where('date_from','<',Carbon::now()->addDays(15))->orderBy('date_from')->get();
        $events_all = Event::where('date_from','>',Carbon::now()->subDays(60))->where('date_from','<',Carbon::now()->addDays(60))->orderBy('date_from')->get();
        
        $event_list = $events->merge($events_all);
        
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 11)->orderBy('sort_order')->get();
        $roles = Role::query()->whereIn('name', config('site.roles_for_crmSalesPlan'))->orderBy('sort_order')->get();

        return view('site::admin.crmSalesPlan.create', compact('divisions','projects','regions','users','creator', 'file_types','event_list','roles'));
    }
    
    public function edit(crmSalesPlan $crmSalesPlan)
    {  
        
        $divisions = CrmDivision::query()->orderBy('name')->get();
        $projects = CrmProject::query()->orderBy('name')->get();
        $users = User::query()->whereHas('roles', function ($q) {
                                                $q->whereHas('percrmSalesPlans', function ($q) {$q->where('name','admin_crmSalesPlans_create');});
                                })
                    ->orderBy('name')->get();
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 11)->orderBy('sort_order')->get();
        $files = $crmSalesPlan->files()->get();
        $events = Event::where('date_from','>',Carbon::now()->subDays(7))->where('date_from','<',Carbon::now()->addDays(15))->orderBy('date_from')->get();
        $events_all = Event::where('date_from','>',Carbon::now()->subDays(60))->where('date_from','<',Carbon::now()->addDays(60))->orderBy('date_from')->get();

        $event_list = $events->merge($events_all);

        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $roles = Role::query()->whereIn('name', config('site.roles_for_crmSalesPlan'))->orderBy('sort_order')->get();
        return view('site::admin.crmSalesPlan.edit', compact('crmSalesPlan','divisions','projects','regions','users','files','file_types','roles','event_list'));
    }
   
    public function store(crmSalesPlanRequest $request)
	{

	    $request->user()->crmSalesPlansCreated()
            ->save($crmSalesPlan = $this->crmSalesPlans->create($request->input('crmSalesPlan')));
        $crmSalesPlan->update(['status_id'=>'1']);
        
        if($request->filled('users')){
            $users = (collect($request->input('users')))->map(function ($main, $user_id) {
                
                return new crmSalesPlanUser([
                    'user_id' => $user_id,
                    'main'=> !empty($main['main']) ? 1 : 0
                ]);
            });
            
            $crmSalesPlan->crmSalesPlanUsers()->saveMany($users);
        }


        if($request->filled('clients')){
            $users = (collect($request->input('clients')))->map(function ($client, $user_id){

                return new crmSalesPlanClient([
                    'client_id' => $user_id, 'comment' =>$client['comment']
                ]);
            });

            $crmSalesPlan->crmSalesPlanClients()->saveMany($users);
        }

        $this->setFiles($request, $crmSalesPlan);
      //  event(new crmSalesPlanCreateEvent($crmSalesPlan));
        
        return redirect()->route('admin.crmSalesPlans.show',$crmSalesPlan)->with('success', trans('site::admin.crmSalesPlan.created'));
    }
    
    
    public function update(crmSalesPlanRequest $request, crmSalesPlan $crmSalesPlan)
	{

        $crmSalesPlan
            ->update($request->except(['_token', '_method', '_create', 'users','status_id'])['crmSalesPlan']);
       
        $crmSalesPlan->crmSalesPlanUsers()->delete();
        
        if($request->filled('users')){
            $users = (collect($request->input('users')))->map(function ($main, $user_id) {
                
                return new crmSalesPlanUser([
                    'user_id' => $user_id,
                    'main'=> !empty($main['main']) ? 1 : 0
                ]);
            });
            
            $crmSalesPlan->crmSalesPlanUsers()->saveMany($users);
        }

        $crmSalesPlan->crmSalesPlanClients()->delete();

        if($request->filled('clients')){
            $users = (collect($request->input('clients')))->map(function ($client, $user_id){

                return new crmSalesPlanClient([
                    'client_id' => $user_id, 'comment' =>$client['comment']
                ]);
            });

            $crmSalesPlan->crmSalesPlanClients()->saveMany($users);
        }
      
        
       $oldFiles = ($this->setFiles($request, $crmSalesPlan))->pluck('id')->toArray();
       
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
        
        return redirect()->route('admin.crmSalesPlans.show',$crmSalesPlan)->with('success', trans('site::admin.crmSalesPlan.updated'));
    }
    

    public function status(crmSalesPlanStatusRequest $request, crmSalesPlan $crmSalesPlan)
    {   
    
       // $text="Статус изменен: " .$crmSalesPlan->status->name ." => " .crmSalesPlanStatus::query()->findOrFail($request->input('status_id'))->name;
        $crmSalesPlan->update(['status_id'=>$request->input('status_id')]);       
        if(!empty($request->input('crmSalesPlan.result'))) {
            $crmSalesPlan->update(['result'=>$request->input('crmSalesPlan.result')]);       
        }
        
        $crmSalesPlan=crmSalesPlan::find($crmSalesPlan->id);
       
        event(new crmSalesPlanStatusChangeEvent($crmSalesPlan));
        
        return redirect()->route('admin.crmSalesPlans.show', $crmSalesPlan)->with('success', 'Статус обновлен');
    }
    
    
    public function destroy(crmSalesPlan $crmSalesPlan)
    {
        $this->authorize('delete', $crmSalesPlan);
        if ($crmSalesPlan->delete()) {
            Session::flash('success', trans('site::admin.crmSalesPlan.deleted'));
            //return redirect()->route('admin.crmSalesPlans.index');
        } else {
            Session::flash('error', trans('site::admin.crmSalesPlan.error_deleted'));
        }
        $json['redirect'] = route('admin.crmSalesPlans.index');

        return response()->json($json);
      //   return redirect()->route('admin.crmSalesPlans.index');
    }
    
    private function getFiles(crmSalesPlanRequest $request, crmSalesPlan $crmSalesPlan = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($crmSalesPlan)) {
            $files = $files->merge($crmSalesPlan->files);
        }

        return $files;
    }

    private function setFiles(crmSalesPlanRequest $request, crmSalesPlan $crmSalesPlan)
    {
        $old_files=$crmSalesPlan->files;
        $crmSalesPlan->detachFiles();
        
        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $crmSalesPlan->files()->save(File::find($file_id));
                }
            }
        }
       
        return $crmSalesPlan->files;
        //$this->files->deleteLostFiles();
    }
    
}