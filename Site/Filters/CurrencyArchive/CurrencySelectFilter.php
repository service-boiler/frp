<?php

namespace ServiceBoiler\Prf\Site\Filters\CurrencyArchive;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\AddressType;
use ServiceBoiler\Prf\Site\Models\Currency;
use ServiceBoiler\Prf\Site\Models\Region;

class CurrencySelectFilter extends WhereFilter
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
        return 'currency_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'currency_id';

    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return Currency::has('archives')
            ->pluck('title', 'id')
            ->prepend(trans('site::messages.select_from_list'), '')
            ->toArray();
    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::archive.currency_id');
    }
}