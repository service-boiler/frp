<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BooleanFilter;
//use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\FerroliDangerSingleSelect;

class ProductHasOldPriceFilter extends BooleanFilter
{
    //use BootstrapSelect;
    use FerroliDangerSingleSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && $this->filled($this->name())) {
            switch ($this->get($this->name())) {
                case "1":
                    $builder = $builder->whereHas('prices', function ($query) {
                        $query->where($this->column(), config('site.defaults.guest.price_old_eur_type_id'));
                    });
                    break;
                case "0":
                    $builder = $builder->whereDoesntHave('prices', function ($query) {
                        $query->where($this->column(), config('site.defaults.guest.price_old_eur_type_id'));
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
        return 'old_price';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'type_id';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::product.has_old_price');
    }
}
