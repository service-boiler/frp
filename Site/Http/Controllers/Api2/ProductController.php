<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api2;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\EnabledFilter;
use ServiceBoiler\Prf\Site\Filters\Product\MountingFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductCanShowFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductMounterFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSortByIdFilter;
use ServiceBoiler\Prf\Site\Filters\Product\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\ProductCanBuyFilter;
use ServiceBoiler\Prf\Site\Filters\Product\LimitFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductForsaleFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSortOrderFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductShowFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductIsBoilerFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductBoilerVolumeMinFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductBoilerVolumeMaxFilter;
use ServiceBoiler\Prf\Site\Filters\Product\HasEquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Product\HasNameFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductHasPricesFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSpecCirclesFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSpecCombustFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSpecFuelFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSpecMountFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductPriceMinFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductPriceMaxFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSpecPowerMinFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSpecPowerMaxFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSpecTypeheatFilter;

use ServiceBoiler\Prf\Site\Http\Resources\ProductApi2Collection;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\Serial;
use ServiceBoiler\Prf\Site\Repositories\ProductRepository;

class ProductController extends Controller
{
    protected $products;

    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $products
     */
    public function __construct(ProductRepository $products)
    {
        $this->products = $products;
    }

    public function show(Product $product)
    {
        return new ProductApi2Collection(Product::find($product));
    }

   public function index()
    {
        $this->products->applyFilter(new ProductCanShowFilter());
        $this->products->applyFilter(new ProductSortByIdFilter());

        return new ProductApi2Collection($this->products->all());
    }

    

}