<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ServiceBoiler\Prf\Site\Http\Requests\FileRequest;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use Illuminate\Routing\Controller;

class FileController extends Controller
{

    use AuthorizesRequests;

    protected $files;
    protected $types;

    /**
     * Create a new controller instance.
     *
     * @param FileRepository $files
     * @param FileTypeRepository $types
     */
    public function __construct(FileRepository $files, FileTypeRepository $types)
    {
        $this->files = $files;
        $this->types = $types;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->types->trackFilter();

        return view('site::file.index', [
            'repository' => $this->files,
            'items'      => $this->files->paginate(config('site.per_page.file', 10), ['files.*'])
        ]);
    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  FileRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Throwable
	 */
    public function store(FileRequest $request)
    {  
        $this->authorize('create', File::class);
        $f = $request->file('path');
        if(empty($f)){return null;}
        $file = new File(array_merge($request->only(['type_id']), [
            'path'    => Storage::disk($request->input('storage'))->putFile(config('site.files.path'), new \Illuminate\Http\File($f->getPathName())),
            'mime'    => $f->getMimeType(),
            'storage' => $request->input('storage'),
            'size'    => $f->getSize(),
            'name'    => $f->getClientOriginalName(),
        ]));

        $file->save();

        if($file->fileable_type=='tenders'){
            $receiver_id = Auth::user()->getKey();
            $text='Добавлен файл ' .$file->name;
            $file->fileable->messages()->save(Auth::user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        }    
       
        //ProcessFile::dispatch($file)->onQueue('images');
        
        return response()->json([
            'file' => view('site::file.create.card')
                ->with('file', $file)
                ->with('success', trans('site::file.loaded'))
                ->render(),
        ]);
    }
    public function storeSingle(FileRequest $request)
    {
        $this->authorize('create', File::class);
        $f = $request->file('path');
        if(empty($f)){return null;}
        $file = new File(array_merge($request->only(['type_id']), [
            'path'    => Storage::disk($request->input('storage'))->putFile(config('site.files.path'), new \Illuminate\Http\File($f->getPathName())),
            'mime'    => $f->getMimeType(),
            'storage' => $request->input('storage'),
            'size'    => $f->getSize(),
            'name'    => $f->getClientOriginalName(),
        ]));

        $file->save();

        if($file->fileable_type=='tenders'){
            $receiver_id = Auth::user()->getKey();
            $text='Добавлен файл ' .$file->name;
            $file->fileable->messages()->save(Auth::user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        }

        //ProcessFile::dispatch($file)->onQueue('images');

        return response()->json([
            'update' => [
                '#files' => view('site::admin.file.edit')
                    ->with('file', $file)
                    ->render(),
            ],
        ]);

    }

	/**
	 * @param File $file
	 *
	 * @return mixed
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function show(File $file)
    {   
         if (!$file->exists()) {
            abort(404);
        }
        
        if (!is_null($file->fileable)) {
            $this->authorize('view', $file);
           
        }
        
        $file->increment('downloads');
        $file->update(['downloaded_at' => Carbon::now()]);
        
        return Storage::disk($file->storage)->download($file->path, Str::ascii($file->name));
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param File $file
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
    public function destroy(File $file)
    {
        if($file->fileable_type=='tenders'){
            $receiver_id = Auth::user()->getKey();
            $text='Удален файл ' .$file->name;
            $file->fileable->messages()->save(Auth::user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        }    
       
        $json = [];
        $file_id = $file->id;
        if ($file->delete()) {
            $json['remove'][] = '#file-' . $file_id;
        }

        return response()->json($json);

    }
}