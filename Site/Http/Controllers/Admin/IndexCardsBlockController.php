<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\IndexCardsBlockRequest;
use ServiceBoiler\Prf\Site\Models\IndexCardsBlock;
use ServiceBoiler\Prf\Site\Repositories\IndexCardsBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\ImageRepository;

class IndexCardsBlockController extends Controller
{
	 use AuthorizesRequests, StoreImages;
	 
    protected $indexCardsBlocks;
    protected $images;

    /**
     * Create a new controller instance.
     *
     * @param IndexCardsBlockRepository $indexCardsBlocks
     */
    public function __construct(IndexCardsBlockRepository $indexCardsBlocks, ImageRepository $images)
    {
        $this->indexCardsBlocks = $indexCardsBlocks;
        $this->images = $images;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {//dd(Route::getCurrentRoute()->uri);
        $this->indexCardsBlocks->trackFilter()
		  ->applyFilter(new Filters\BySortOrderFilter());

        return view('site::admin.block.index_cards_block.index', [
            'repository' => $this->indexCardsBlocks,
            'indexCardsBlocks'  => $this->indexCardsBlocks->paginate(config('site.per_page.index_cards_block', 10), ['index_cards_blocks.*'])
        ]);
    }

    public function create(IndexCardsBlockRequest $request)
    {
        $image = $this->getImage($request);
        return view('site::admin.block.index_cards_block.create', compact('image'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(IndexCardsBlockRequest $request)
    {

        $indexCardsBlock = $this->indexCardsBlocks->create(array_merge(
            $request->input('indexCardsBlock'),
            [
                'show_ferroli'     => $request->filled('indexCardsBlock.show_ferroli'),
                'show_market_ru' => $request->filled('indexCardsBlock.show_market_ru')
            ]
        ));

        return $redirect = redirect()->route('admin.index_cards_blocks.index')->with('success', trans('site::admin.index_cards_block_created'));
    }


    /**
     * @param IndexCardsBlock $indexCardsBlock
     * @return \Illuminate\Http\Response
     */
    public function edit(IndexCardsBlockRequest $request, IndexCardsBlock $indexCardsBlock)
    {
        $image = $this->getImage($request, $indexCardsBlock);
        
        return view('site::admin.block.index_cards_block.edit', compact('indexCardsBlock', 'image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  indexCardsBlockRequest $request
     * @param  indexCardsBlock $indexCardsBlock
     * @return \Illuminate\Http\Response
     */
    public function update(IndexCardsBlockRequest $request, IndexCardsBlock $indexCardsBlock)
    {
			$indexCardsBlock->update(array_merge(
            $request->input('indexCardsBlock'),
            [
                'show_ferroli'     => $request->filled('indexCardsBlock.show_ferroli'),
                'show_market_ru' => $request->filled('indexCardsBlock.show_market_ru')
            ]
        ));


        return redirect()->route('admin.index_cards_blocks.index')->with('success', trans('site::admin.index_cards_block_updated'));
    }

    public function destroy(IndexCardsBlock $indexCardsBlock)
    {

        if ($indexCardsBlock->delete()) {
            return redirect()->route('admin.index_cards_blocks.index')->with('success', trans('site::admin.index_cards_block_deleted'));
        } else {
            return redirect()->route('admin.index_cards_blocks.show', $indexCardsBlock)->with('error', trans('site::admin.block.index_cards_block.error.deleted'));
        }
    }

}