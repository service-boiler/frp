<?php

namespace ServiceBoiler\Prf\Site\Filters\Scheme;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Block;
use ServiceBoiler\Prf\Site\Models\ProductType;

class BlockSelectFilter extends WhereFilter
{

    use BootstrapSelect;

    protected $render = true;

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options():array
    {
        $options = Block::has('schemes')->orderBy('name')->pluck('name', 'id');
        $options->prepend(trans('site::messages.select_from_list'), '');
        return $options->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'block_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'schemes.block_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::scheme.block_id');
    }

}