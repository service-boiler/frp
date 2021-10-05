<?php

namespace ServiceBoiler\Prf\Site\Filters\Storehouse;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\ProductGroup;
use ServiceBoiler\Prf\Site\Models\ProductGroupType;

class StorehouseProductGroupTypeFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($group_type = $this->get($this->name()))) {
            $builder = $builder->whereHas('itemProducts', function ($query) use ($group_type) {
                $query->leftjoin('product_groups','products.group_id','=','product_groups.id')->where('product_groups.type_id',$group_type);
                    
            });
        }
        //dump($builder->toSql());
       // dd($builder->getBindings());
        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'group_type';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'group_type';

    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return ProductGroupType::orderBy('name')
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
        return trans('site::distributor_sales.filter.group_type');
    }
}