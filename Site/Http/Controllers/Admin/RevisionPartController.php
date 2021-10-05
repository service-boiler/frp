<?php
namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use ServiceBoiler\Prf\Site\Exports\Excel\RevisionPartExcel;
use ServiceBoiler\Prf\Site\Events\RevisionPartNoticeEvent;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\RevisionPart;
use ServiceBoiler\Prf\Site\Models\RevisionPartProductRelation;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\RevisionPartRepository;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\RevisionPartRequest;

class RevisionPartController extends Controller
{
    use AuthorizesRequests;
   
    protected $revisionParts;
    protected $files;
    
    public function __construct(RevisionPartRepository $revisionParts, FileRepository $files)
    {
        $this->revisionParts = $revisionParts;
        $this->files = $files;
    }
    
    public function index(RevisionPartRequest $request)
    {  
        $this->revisionParts->trackFilter();
        
        if ($request->has('excel')) {
			(new RevisionPartExcel())->setRepository($this->revisionParts)->render();
		}
        return view('site::admin.revision_part.index', [
            'repository' => $this->revisionParts,
            'revisionParts'    => $this->revisionParts->paginate($request->input('filter.per_page', config('site.per_page.revisionParts', 100)), ['revision_parts.*'])
        ]);
    } 
    
    public function show(RevisionPart $revisionPart)
    {
        $files = $revisionPart->files()->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 12)->orderBy('sort_order')->get();
        
        return  view('site::admin.revision_part.show',compact('revisionPart','files', 'file_types'));
    
    
    }
    
    public function create(RevisionPartRequest $request)
    {   
        $files = $this->getFiles($request);
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 12)->orderBy('sort_order')->get();
        $products = Product::query()->where('enabled',1)->whereHas('group',function ($query) {$query->whereIn('product_groups.type_id',['1']);})->orderBy('name')->get();
        $equipments = Equipment::query()->where('enabled',1)->orderBy('name')->get();
        return view('site::admin.revision_part.create', compact('products','equipments', 'file_types','files'));
    }
    
    public function edit(RevisionPart $revisionPart)
    {  
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 12)->orderBy('sort_order')->get();
        $files = $revisionPart->files()->get();
        $products = Product::query()->where('enabled',1)->whereIn('type_id',[1,2,3])->whereHas('group',function ($query) {$query->whereIn('product_groups.type_id',['1']);})->orderBy('name')->get();
        $equipments = Equipment::query()->where('enabled',1)->orderBy('name')->get();
        
        return view('site::admin.revision_part.edit', compact('products','revisionPart','equipments','files', 'file_types'));
    }
   
    public function store(RevisionPartRequest $request)
	{
        $request->user()->revisionPartsCreated()
            ->save($revisionPart = $this->revisionParts
                                            ->create(array_merge($request->input('revisionPart'),[
                    'interchange'     => $request->filled('revisionPart.interchange'),
                    'enabled'     => $request->filled('revisionPart.enabled'),
                    'public'     => $request->filled('revisionPart.public')
                ]
                )));
        
        if($request->filled('products')){
            $products = (collect($request->input('products')))->map(function ($start_serial, $product_id) {
                return new RevisionPartProductRelation([
                    'revision_product_id' => $product_id,
                    'start_serial'=> $start_serial['start_serial']
                ]);
            });
            
            $revisionPart->revisionPartProductRelations()->saveMany($products);
        }
      
        $this->setFiles($request, $revisionPart);
        
        return redirect()->route('admin.revision_parts.show',$revisionPart)->with('success', trans('site::admin.revision_part.created'));
    }
    
    
    public function update(RevisionPartRequest $request, RevisionPart $revisionPart)
	{
     
        $revisionPart
            ->update(array_merge($request->except(['_token', '_method', '_create', 'products'])['revisionPart'],[
                    'interchange'     => $request->filled('revisionPart.interchange'),
                    'enabled'     => $request->filled('revisionPart.enabled'),
                    'public'     => $request->filled('revisionPart.public')
                ]
                ));
       
        $revisionPart->revisionPartProductRelations()->delete();
        
        if($request->filled('products')){
            $products = (collect($request->input('products')))->map(function ($start_serial, $product_id) {
                return new RevisionPartProductRelation([
                    'revision_product_id' => $product_id,
                    'start_serial'=> $start_serial['start_serial']
                ]);
            });
            
            $revisionPart->revisionPartProductRelations()->saveMany($products);
        }
      
       $oldFiles = ($this->setFiles($request, $revisionPart))->pluck('id')->toArray();
       
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
        
        return redirect()->route('admin.revision_parts.show',$revisionPart)->with('success', trans('site::admin.revision_part.created'));
    }
    

    public function destroy(RevisionPart $revisionPart)
    {
       // $this->authorize('delete', $revisionPart);
        if ($revisionPart->delete()) {
            Session::flash('success', trans('site::admin.revision_part.deleted'));
            //return redirect()->route('admin.revision_parts.index');
        } else {
            Session::flash('error', trans('site::admin.revision_part.error_deleted'));
        }
        $json['redirect'] = route('admin.revision_parts.index');

        return response()->json($json);
      //   return redirect()->route('admin.revision_parts.index');
    }
    
    public function notice(RevisionPart $revisionPart)
    {
       $manager = Auth()->user();
        $ascs=User::whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})->where('active','1')->get();
        
        $text = "Внимание! Уведомление о изменении деталей в оборудовании. " .$revisionPart->description .' <a href="'. route('admin.revision_parts.show',$revisionPart) .'">Подробнее</a>';
        
        foreach($ascs as $user) {
        
            $user->messages()->save($message = $manager->outbox()->create(['receiver_id'=>$user->id, 'personal'=>0, 'text'=>$text]));
        
        }
       $revisionPart->update(['public'=>1]);
       
       Session::flash('success', trans('site::admin.revision_part.notice_sended'));
      
       $json['redirect'] = route('admin.revision_parts.show',$revisionPart);
        return response()->json($json);

    }
    
    
    private function getFiles(RevisionPartRequest $request, RevisionPart $revisionPart = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($revisionPart)) {
            $files = $files->merge($revisionPart->files);
        }

        return $files;
    }

    private function setFiles(RevisionPartRequest $request, RevisionPart $revisionPart)
    {
        $old_files=$revisionPart->files;
        $revisionPart->detachFiles();
        
        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $revisionPart->files()->save(File::find($file_id));
                }
            }
        }
       
        return $revisionPart->files;
        //$this->files->deleteLostFiles();
    }
    
}