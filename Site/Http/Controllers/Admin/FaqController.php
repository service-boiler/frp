<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\FaqRequest;
use ServiceBoiler\Prf\Site\Models\Faq;
use ServiceBoiler\Prf\Site\Repositories\FaqRepository;

class FaqController extends Controller
{

    protected $faqEntries;

    /**
     * Create a new controller instance.
     *
     * @param FaqRepository $faqEntries
     */
    public function __construct(FaqRepository $faqEntries)
    {
        $this->faqEntries = $faqEntries;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->faqEntries->trackFilter()
		  ->applyFilter(new Filters\BySortOrderFilter());

        return view('site::admin.faq.index', [
            'repository' => $this->faqEntries,
            'faqEntries'  => $this->faqEntries->paginate(config('site.per_page.faq', 100), ['faqs.*'])
        ]);
    }

    public function create()
    {
        return view('site::admin.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request)
    {
        
        $faq = $this->faqEntries->create($request->input('faq'));

        return $redirect = redirect()->route('admin.faq.index')->with('success', trans('site::admin.faq_created'));
    }


    /**
     * @param Faq $faqEntry
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        return view('site::admin.faq.show', compact('faq'));
    }

    public function edit(Faq $faqEntry)
    {
        return view('site::admin.block.video_block.edit', compact('faqEntry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  faqEntryRequest $request
     * @param  faqEntry $faqEntry
     * @return \Illuminate\Http\Response
     */
    public function update(FaqRequest $request, Faq $faqEntry)
    {
			$faqEntry->update(array_merge(
            $request->input('faqEntry'),
            [
                'show_ferroli'     => $request->filled('faqEntry.show_ferroli'),
                'show_market_ru' => $request->filled('faqEntry.show_market_ru')
            ]
        ));


        return redirect()->route('admin.video_blocks.index')->with('success', trans('site::admin.video_block_updated'));
    }

    public function destroy(Faq $faqEntry)
    {

        if ($faqEntry->delete()) {
            return redirect()->route('admin.video_blocks.index')->with('success', trans('site::admin.video_block_deleted'));
        } else {
            return redirect()->route('admin.video_blocks.show', $faqEntry)->with('error', trans('site::admin.block.video_block.error.deleted'));
        }
    }

}