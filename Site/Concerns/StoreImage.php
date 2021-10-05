<?php

namespace ServiceBoiler\Prf\Site\Concerns;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Contracts\SingleImageable;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Models\Image;

trait StoreImage
{
    /**
     * @param \ServiceBoiler\Prf\Site\Http\Requests\ImageRequest $request
     * @param \ServiceBoiler\Prf\Site\Contracts\SingleImageable $imageable
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeImage(ImageRequest $request, SingleImageable $imageable)
    {
        $file = $request->file('path');

        $image = new Image([
            'path'    => Storage::disk($request->input('storage'))->putFile('', new File($file->getPathName())),
            'mime'    => $file->getMimeType(),
            'storage' => $request->input('storage'),
            'size'    => $file->getSize(),
            'name'    => $file->getClientOriginalName(),
        ]);

        $image->save();

        return response()->json([
            'update' => [
                '#images' => view('site::admin.image.edit')
                    ->with('image', $image)
                    ->render()
            ]
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \ServiceBoiler\Prf\Site\Contracts\SingleImageable $imageable
     * @return Image
     */
    public function getImage(Request $request, SingleImageable $imageable = null)
    {
        $name = null;
        $image = null;
        if (!is_null($imageable)) {
            $name = config('site.' . $imageable->imageStorage() . '.dot_name');
        }

        if (!is_null($name) && $request->old($name)) {
            $image = Image::findOrFail($request->old($name));
        } elseif (!is_null($imageable)) {
            $image = $imageable->image;
        }

        return $image;
    }

}