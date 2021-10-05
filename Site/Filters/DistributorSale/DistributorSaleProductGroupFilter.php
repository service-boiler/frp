<?php

namespace ServiceBoiler\Prf\Site\Filters\DistributorSale;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\ProductGroup;

class DistributorSaleProductGroupFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {   
        if ($this->canTrack() && !is_null($group_id = $this->get($this->name()))) {
            $builder = $builder->whereHas('product', function ($query) use ($group_id) {
                $query->where(DB::raw($this->column()), $this->operator(), $group_id);
                
            });
        }
      //  dump($builder->toSql());
        //dd($builder->getBindings());
        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'group_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'group_id';

    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return ProductGroup::whereHas('products', function ($query) {
            if(auth()->user()->admin == 1){
                $query->has('distributorSales');
            } else{
                $query->whereHas('distributorSales', function ($query){
                    $query->where('user_id', auth()->user()->getAuthIdentifier());
                });
            }

        })->orderBy('name')
            ->pluck('name', 'id')
            ->prepend(trans('site::messages.select_no_matter'), '')
            ->toArray();
    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::distributor_sales.filter.group');
    }
}