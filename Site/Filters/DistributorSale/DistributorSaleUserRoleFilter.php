<?php

namespace ServiceBoiler\Prf\Site\Filters\DistributorSale;

use ServiceBoiler\Rbac\Models\Role;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;

use ServiceBoiler\Repo\Filters\BootstrapDropdownCheckbox;
use ServiceBoiler\Repo\Filters\CheckboxFilter;

class DistributorSaleUserRoleFilter extends CheckboxFilter
{
    use BootstrapDropdownCheckbox;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if($this->has($this->name()) && !is_null($roles = $this->get($this->name()))){
        
            $builder = $builder->whereHas('user', function ($query) use ($roles) {
                $query->leftjoin('role_user','users.id','=','role_user.user_id')->whereIn('role_id',$roles);
            });
            
        }
        //dump($builder->toSql());
        //dd($builder->getBindings());
        return $builder;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return Role::where('id', '!=', 1)->get()->pluck('title', 'id')->toArray();
    }

    /**
     * @return string
     */
    function name(): string
    {
        return 'roles';
    }

    /**
     * Options
     *
     * @return string
     */
    function value(): string
    {
        return '';
    }

    protected function label()
    {
        return trans('rbac::role.roles');
    }
}