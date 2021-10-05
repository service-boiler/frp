<?php

namespace ServiceBoiler\Prf\Site\Filters\Act;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\User;

class ActUserFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($contragent_id = $this->get($this->name()))) {
            $builder = $builder->where(DB::raw($this->column()), $this->operator(), $contragent_id);
        }

        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'acts.user_id';

    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return User::query()->has('acts')->orderBy('name')
            ->pluck('name', 'id')
//            ->map(function ($item, $key) {
//                return str_limit($item, config('site.name_limit', 25));
//            })
            ->prepend(trans('site::messages.select_no_matter'), '')
            ->toArray();
    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::act.user_id');
    }
    
    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'max-width: 120px;');

        return $attributes;
    }

}