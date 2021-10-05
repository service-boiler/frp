<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\IndexQuadroBlockRequest;
use ServiceBoiler\Prf\Site\Models\IndexQuadroBlock;
use ServiceBoiler\Prf\Site\Repositories\IndexQuadroBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\ImageRepository;

class IndexQuadroBlockController extends Controller
{
	 use AuthorizesRequests, StoreImages;
	 
    protected $indexQuadroBlocks;
    protected $images;

    /**
     * Create a new controller instance.
     *
     * @param IndexQuadroBlockRepository $indexQuadroBlocks
     */
    public function __construct(IndexQuadroBlockRepository $indexQuadroBlocks, ImageRepository $images)
    {
        $this->indexQuadroBlocks = $indexQuadroBlocks;
        $this->images = $images;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indexQuadroBlocks = $this->indexQuadroBlocks
		  ->applyFilter(new Filters\BySortOrderFilter())
          ->all(['index_quadro_blocks.*']);

        return view('site::admin.block.index_quadro_block.index', compact('indexQuadroBlocks'));
    }

    public function create(IndexQuadroBlockRequest $request)
    {
        $image = $this->getImage($request);
        return view('site::admin.block.index_quadro_block.create', compact('image'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(IndexQuadroBlockRequest $request)
    {

        $indexQuadroBlock = $this->indexQuadroBlocks->create(array_merge(
            $request->input('indexQuadroBlock'),
            [
                'enabled'     => $request->filled('indexQuadroBlock.enabled')
            ]
        ));

        return $redirect = redirect()->route('admin.index_quadro_blocks.index')->with('success', trans('site::admin.index_quadro_block_created'));
    }


    /**
     * @param IndexQuadroBlock $indexQuadroBlock
     * @return \Illuminate\Http\Response
     */
    public function edit(IndexQuadroBlockRequest $request, IndexQuadroBlock $indexQuadroBlock)
    {
        $image = $this->getImage($request, $indexQuadroBlock);
        
        return view('site::admin.block.index_quadro_block.edit', compact('indexQuadroBlock', 'image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  indexQuadroBlockRequest $request
     * @param  indexQuadroBlock $indexQuadroBlock
     * @return \Illuminate\Http\Response
     */
    public function update(IndexQuadroBlockRequest $request, IndexQuadroBlock $indexQuadroBlock)
    {
			$indexQuadroBlock->update(array_merge(
            $request->input('indexQuadroBlock'),
            [
                'enabled'     => $request->filled('indexQuadroBlock.enabled')
            ]
        ));


        return redirect()->route('admin.index_quadro_blocks.index')->with('success', trans('site::admin.index_quadro_block_updated'));
    }

    public function destroy(IndexQuadroBlock $indexQuadroBlock)
    {

        if ($indexQuadroBlock->delete()) {
            return redirect()->route('admin.index_quadro_blocks.index')->with('success', trans('site::admin.index_quadro_block_deleted'));
        } else {
            return redirect()->route('admin.index_quadro_blocks.show', $indexQuadroBlock)->with('error', trans('site::admin.block.index_quadro_block.error.deleted'));
        }
    }

}