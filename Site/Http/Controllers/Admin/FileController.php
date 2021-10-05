<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Contracts\Fileable;
use ServiceBoiler\Prf\Site\Http\Requests\FileRequest;
use ServiceBoiler\Prf\Site\Models\File;
use Illuminate\Support\Str;

class FileController extends Controller
{

    use StoreFiles;

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  FileRequest $request
	 * @param Fileable $fileable
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
    public function store(FileRequest $request, Fileable $fileable = null)
    {    
        return $this->storeFiles($request, $fileable);

    }

    public function show(File $file)
    {
	    return Storage::disk($file->getAttribute('storage'))->download($file->getAttribute('path'), Str::ascii($file->getAttribute('name')));
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
        $json = [];
        if ($file->delete()) {
            $json['remove'][] = '#file-' . $file->getAttribute('id');
        }

        return response()->json($json);

    }
}