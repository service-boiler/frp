<?php

namespace ServiceBoiler\Prf\Site\Filters\Authorization;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;

class AuthorizationTypeSelectFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($this->get($this->name()))) {
            $builder = $builder
                ->join(
                'authorization_type',
                'authorization_type.authorization_id',
                '=',
                'id'
            )
                ->where(DB::raw($this->column()), $this->operator(), $this->get($this->name()));
        }

        return $builder;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return AuthorizationType::query()->with('brand')
            ->whereHas('authorizations', function ($query) {
                
            })->get()
            ->map(function ($query) {
                return ['key' => $query->id, 'value' => $query->name . ' ' . $query->brand->name];
            })->sortBy('value')
            ->pluck('value', 'key')
            ->prepend(trans('site::messages.select_from_list'), '')
            ->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'type_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'authorization_type.type_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::authorization.help.type_id');
    }
}