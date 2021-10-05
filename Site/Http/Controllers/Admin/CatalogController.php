<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Filters\Catalog\CatalogShowFerroliBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Catalog\CatalogShowMarketRuBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Catalog\CatalogShowLamborghiniBoolFilter;
use ServiceBoiler\Prf\Site\Filters\CatalogEnabledFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\CatalogRequest;
use ServiceBoiler\Prf\Site\Models\Catalog;
use ServiceBoiler\Prf\Site\Repositories\CatalogRepository;
use ServiceBoiler\Prf\Site\Repositories\ImageRepository;

class CatalogController extends Controller
{

    use AuthorizesRequests, StoreImages;

    protected $catalogs;
    protected $images;

    /**
     * Create a new controller instance.
     *
     * @param CatalogRepository $catalogs
     * @param ImageRepository $images
     */
    public function __construct(CatalogRepository $catalogs, ImageRepository $images)
    {
        $this->catalogs = $catalogs;
        $this->images = $images;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->catalogs->trackFilter()
            ->applyFilter(new CatalogEnabledFilter())
            ->pushTrackFilter(CatalogShowFerroliBoolFilter::class)
            ->pushTrackFilter(CatalogShowMarketRuBoolFilter::class)
            ->pushTrackFilter(CatalogShowLamborghiniBoolFilter::class);

        return view('site::admin.catalog.index', [
            'repository' => $this->catalogs,
            'catalogs'   => $this->catalogs->paginate(config('site.per_page.catalog', 10), ['catalogs.*'])
        ]);
    }

    /**
     * @param Catalog $catalog
     * @return \Illuminate\Http\Response
     */
    public function show(Catalog $catalog)
    {
        return view('site::admin.catalog.show', compact('catalog'));
    }

    /**
     * @param CatalogRequest $request
     * @param Catalog|null $catalog
     * @return \Illuminate\Http\Response
     */
    public function create(CatalogRequest $request, Catalog $catalog = null)
    {
        $parent_catalog_id = !is_null($catalog) ? $catalog->getAttribute('id') : null;
        $tree = $this->catalogs->tree();
        $image = $this->getImage($request);

        return view('site::admin.catalog.create', compact('parent_catalog_id', 'tree', 'image'));
    }

    /**
     * @param  CatalogRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogRequest $request)
    {

        $catalog = $this->catalogs->create(array_merge(
            $request->input('catalog'),
            [
                'enabled'          => $request->filled('catalog.enabled'),
                'mounter_enabled'  => $request->filled('catalog.mounter_enabled'),
                'show_ferroli'     => $request->filled('catalog.show_ferroli'),
                'show_market_ru'     => $request->filled('catalog.show_market_ru'),
                'show_lamborghini' => $request->filled('catalog.show_lamborghini')
            ]
        ));

        return redirect()->route('admin.catalogs.show', $catalog)->with('success', trans('site::catalog.created'));
    }


	/**
	 * @param CatalogRequest $request
	 * @param  Catalog $catalog
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function edit(CatalogRequest $request, Catalog $catalog)
    {
        $this->authorize('update', $catalog);
        $tree = $this->catalogs->tree();
        $parent_catalog_id = null;
        $image = $this->getImage($request, $catalog);

        return view('site::admin.catalog.edit', compact('tree', 'catalog', 'parent_catalog_id', 'image'));
    }

    /**
     * @param  CatalogRequest $request
     * @param  Catalog $catalog
     * @return \Illuminate\Http\Response
     */
    public function update(CatalogRequest $request, Catalog $catalog)
    {
        $catalog->update(array_merge(
            $request->input('catalog'),
            [
                'enabled'          => $request->filled('catalog.enabled'),
                'mounter_enabled'  => $request->filled('catalog.mounter_enabled'),
                'show_ferroli'     => $request->filled('catalog.show_ferroli'),
                'show_market_ru'     => $request->filled('catalog.show_market_ru'),
                'show_lamborghini' => $request->filled('catalog.show_lamborghini')
            ]
        ));

        return redirect()->route('admin.catalogs.show', $catalog)->with('success', trans('site::catalog.updated'));
    }

    /**
     * @param  Catalog $catalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Catalog $catalog)
    {

        $this->authorize('delete', $catalog);

        $json['redirect'] = $catalog->delete() ? route('admin.catalogs.index') : route('admin.catalogs.show', $catalog);

        return response()->json($json);

    }


    public function tree()
    {
        $tree = $this->catalogs->tree();

        return view('site::admin.catalog.tree', compact('tree'));
    }

}