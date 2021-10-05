<?php

namespace ServiceBoiler\Prf\Site\Filters\UserPrereg;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class RegistredSelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;
    
     function apply($builder, RepositoryInterface $repository)
        {

        if ($this->canTrack() && $this->filled($this->name())) {
            switch ($this->get($this->name())) {
                case "1":
                    $builder = $builder->whereNotNull($this->column());
                    
                    break;
                case "0":
                    $builder = $builder->whereNull($this->column());
                    break;
            }
        }

        return $builder;
    }
    /**
     * @return string
     */
    public function name(): string
    {
        return 'user_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'user_id';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return 'Зарег.';
    }
}