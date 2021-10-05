<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;

class IsShopSelectFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($value = $this->get($this->name()))) {
            $builder = $builder->where(DB::raw($this->column()), $this->operator(), $value);
        }

        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'is_shop';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'is_shop';

    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return [
            '' => trans('site::messages.select_from_list'),
            '0' => trans('site::messages.no'),
            '1' => trans('site::messages.yes')
        ];
    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::address.is_shop');
    }
}