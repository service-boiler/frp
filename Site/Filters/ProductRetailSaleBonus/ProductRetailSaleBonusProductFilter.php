<?php

namespace ServiceBoiler\Prf\Site\Filters\ProductRetailSaleBonus;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Product;

class ProductRetailSaleBonusProductFilter extends WhereFilter
{

    use BootstrapSelect;

    protected $render = true;

    /**
     * @param $builder
     * @param RepositoryInterface $repository
     * @return mixed
     */
    function apply($builder, RepositoryInterface $repository)
    {

        if ($this->canTrack() && $this->filled($this->name())) {
            $builder = $builder->where('product_id', $this->get($this->name()));
        }

        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'product_id';
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        $options = Product::has('retail_sale_bonus')
            ->orderBy('name')
            ->pluck('name', 'id');
        $options->prepend(trans('site::messages.select_from_list'), '');

        return $options->toArray();
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'product_retail_sale_bonuses.product_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::product.retail_sale_bonus.product_id');
    }

}