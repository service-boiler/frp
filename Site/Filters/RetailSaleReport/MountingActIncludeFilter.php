<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounting;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;

class MountingActIncludeFilter extends WhereFilter
{

    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->has($this->name()) && $this->filled($this->name())) {
            if ($this->get($this->name()) == 0) {
                $builder = $builder->whereNull('act_id');
            } else {
                $builder = $builder->whereNotNull('act_id');
            }

        }

        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'act_include';
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return [
            ''  => trans('site::messages.select_no_matter'),
            '1' => trans('site::messages.yes'),
            '0' => trans('site::messages.no'),
        ];
    }

    /**
     * @return string
     */
    public function column(): string
    {
        return 'act_id';
    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::mounting.act_id');
    }
}