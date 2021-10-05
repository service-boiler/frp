<?php

namespace ServiceBoiler\Prf\Site\Filters\Authorization;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;

class AuthorizationRoleSelectFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($this->get($this->name()))) {
            $builder = $builder
                ->join(
                'authorization_roles',
                'authorization_roles.role_id',
                '=',
                'authorizations.role_id'
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
        return AuthorizationRole::query()
            ->pluck('name', 'id')
            ->prepend(trans('site::messages.select_from_list'), '')
            ->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'role';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'authorization_roles.id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::authorization_role.authorization_roles');
    }
}