<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class RepairUserHasCertSelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && $this->filled($this->name())) {
            switch ($this->get($this->name())) {
                case "1":
                    $builder = $builder->whereHas('user', function ($q) {
                            $q->whereHas('childs', function ($q) {
                                $q->whereHas('certificates', function ($q) {
                                    $q->where('type_id',1);
                                });
                            });
                        });
                    break;
                case "0":
                    $builder = $builder->whereDoesntHave('user', function ($q) {
                            $q->whereHas('childs', function ($q) {
                                $q->whereHas('certificates', function ($q) {
                                    $q->where('type_id',1);
                                });
                            });
                        });
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
        return 'has_certs';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'name';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::user.filter_has_child_certs');
    }
}