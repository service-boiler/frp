<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\HeadBannerBlockRequest;
use ServiceBoiler\Prf\Site\Models\HeadBannerBlock;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\ImageRepository;

class HeadBannerBlockController extends Controller
{
	 use AuthorizesRequests, StoreImages;
	 
    protected $headBannerBlocks;
    protected $images;

    /**
     * Create a new controller instance.
     *
     * @param HeadBannerBlockRepository $headBannerBlocks
     */
    public function __construct(HeadBannerBlockRepository $headBannerBlocks, ImageRepository $images)
    {
        $this->headBannerBlocks = $headBannerBlocks;
        $this->images = $images;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headBannerBlocks = $this->headBannerBlocks
		  ->applyFilter(new Filters\BySortOrderFilter())
          ->all(['head_banner_blocks.*']);

        return view('site::admin.block.head_banner_block.index', compact('headBannerBlocks'));
    }

    public function create(HeadBannerBlockRequest $request)
    {
        $image = $this->getImage($request);
        return view('site::admin.block.head_banner_block.create', compact('image'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(HeadBannerBlockRequest $request)
    {

        $headBannerBlock = $this->headBannerBlocks->create(array_merge(
            $request->input('headBannerBlock'),
            [
                'show_ferroli'     => $request->filled('headBannerBlock.show_ferroli'),
                'show_market_ru' => $request->filled('headBannerBlock.show_market_ru')
            ]
        ));

        return $redirect = redirect()->route('admin.head_banner_blocks.index')->with('success', trans('site::admin.head_banner_block_created'));
    }


    /**
     * @param HeadBannerBlock $headBannerBlock
     * @return \Illuminate\Http\Response
     */
    public function edit(HeadBannerBlockRequest $request, HeadBannerBlock $headBannerBlock)
    {
        $image = $this->getImage($request, $headBannerBlock);
        
        return view('site::admin.block.head_banner_block.edit', compact('headBannerBlock', 'image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  headBannerBlockRequest $request
     * @param  headBannerBlock $headBannerBlock
     * @return \Illuminate\Http\Response
     */
    public function update(HeadBannerBlockRequest $request, HeadBannerBlock $headBannerBlock)
    {
			$headBannerBlock->update(array_merge(
            $request->input('headBannerBlock'),
            [
                'show_ferroli'     => $request->filled('headBannerBlock.show_ferroli'),
                'show_market_ru' => $request->filled('headBannerBlock.show_market_ru')
            ]
        ));


        return redirect()->route('admin.head_banner_blocks.index')->with('success', trans('site::admin.head_banner_block_updated'));
    }

    public function destroy(HeadBannerBlock $headBannerBlock)
    {

        if ($headBannerBlock->delete()) {
            return redirect()->route('admin.head_banner_blocks.index')->with('success', trans('site::admin.head_banner_block_deleted'));
        } else {
            return redirect()->route('admin.head_banner_blocks.show', $headBannerBlock)->with('error', trans('site::admin.block.head_banner_block.error.deleted'));
        }
    }

}