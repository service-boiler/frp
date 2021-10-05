<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\VideoBlockRequest;
use ServiceBoiler\Prf\Site\Models\VideoBlock;
use ServiceBoiler\Prf\Site\Repositories\VideoBlockRepository;

class VideoBlockController extends Controller
{

    protected $videoBlocks;

    /**
     * Create a new controller instance.
     *
     * @param VideoBlockRepository $videoBlocks
     */
    public function __construct(VideoBlockRepository $videoBlocks)
    {
        $this->videoBlocks = $videoBlocks;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->videoBlocks->trackFilter()
		  ->applyFilter(new Filters\BySortOrderFilter());

        return view('site::admin.block.video_block.index', [
            'repository' => $this->videoBlocks,
            'videoBlocks'  => $this->videoBlocks->paginate(config('site.per_page.video_block', 10), ['video_blocks.*'])
        ]);
    }

    public function create()
    {
        return view('site::admin.block.video_block.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoBlockRequest $request)
    {

        $videoBlock = $this->videoBlocks->create(array_merge(
            $request->input('videoBlock'),
            [
                'show_ferroli'     => $request->filled('videoBlock.show_ferroli'),
                'show_market_ru' => $request->filled('videoBlock.show_market_ru')
            ]
        ));

        return $redirect = redirect()->route('admin.video_blocks.index')->with('success', trans('site::admin.video_block_created'));
    }


    /**
     * @param VideoBlock $videoBlock
     * @return \Illuminate\Http\Response
     */
    public function edit(VideoBlock $videoBlock)
    {
        return view('site::admin.block.video_block.edit', compact('videoBlock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  videoBlockRequest $request
     * @param  videoBlock $videoBlock
     * @return \Illuminate\Http\Response
     */
    public function update(VideoBlockRequest $request, VideoBlock $videoBlock)
    {
			$videoBlock->update(array_merge(
            $request->input('videoBlock'),
            [
                'show_ferroli'     => $request->filled('videoBlock.show_ferroli'),
                'show_market_ru' => $request->filled('videoBlock.show_market_ru')
            ]
        ));


        return redirect()->route('admin.video_blocks.index')->with('success', trans('site::admin.video_block_updated'));
    }

    public function destroy(VideoBlock $videoBlock)
    {

        if ($videoBlock->delete()) {
            return redirect()->route('admin.video_blocks.index')->with('success', trans('site::admin.video_block_deleted'));
        } else {
            return redirect()->route('admin.video_blocks.show', $videoBlock)->with('error', trans('site::admin.block.video_block.error.deleted'));
        }
    }

}