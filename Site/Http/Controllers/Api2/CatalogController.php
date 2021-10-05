<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api2;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\Catalog\CatalogShowFilter;
use ServiceBoiler\Prf\Site\Filters\CatalogRootFilter;
use ServiceBoiler\Prf\Site\Filters\EnabledFilter;

use ServiceBoiler\Prf\Site\Http\Resources\CatalogTreeCollection;
use ServiceBoiler\Prf\Site\Models\Catalog;
use ServiceBoiler\Prf\Site\Repositories\CatalogRepository;

class CatalogController extends Controller
{
    protected $catalogs;

    /**
     * Create a new controller instance.
     *
     * @param CatalogRepository $catalogs
     */
    public function __construct(CatalogRepository $catalogs)
    {
        $this->catalogs = $catalogs;
    }

    public function show(Catalog $catalog)
    {

        return new CatalogTreeCollection(Catalog::find($catalog));
    }

   public function index()
    {
        $this->catalogs->applyFilter(new EnabledFilter());
        $this->catalogs->applyFilter(new CatalogShowFilter());
        $this->catalogs->applyFilter(new CatalogRootFilter());

        return new CatalogTreeCollection($this->catalogs->all());
    }

    

}