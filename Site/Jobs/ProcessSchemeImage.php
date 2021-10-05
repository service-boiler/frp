<?php

namespace ServiceBoiler\Prf\Site\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Models\Image;

class ProcessSchemeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * @var Image
     */
    protected $image;
    /**
     * @var string
     */
    protected $storage;

    /**
     * Create a new job instance.
     * @param Image $image
     * @param string $storage
     */
    public function __construct(Image $image, string $storage)
    {
        $this->image = $image;
        $this->storage = $storage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $img = \Intervention\Image\Facades\Image::make(Storage::disk($this->storage)->getAdapter()->getPathPrefix() . $this->image->path);
        $img->resize(
            config('site.schemes.size.image.width', 740),
            config('site.schemes.size.image.height', null),
            function ($constraint) {
                $constraint->aspectRatio();
            });
        $img->resizeCanvas(
            config('site.schemes.size.canvas.width', 740),
            config('site.schemes.size.canvas.height', null),
            'center',
            false,
            'rgba(255, 255, 255, 1)'
        );
        $img->save();
        $this->image->size = $img->filesize();
        $this->image->save();
    }
}
