<?php

namespace ServiceBoiler\Prf\Site\Filters\Mission;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;

use ServiceBoiler\Repo\Filters\BootstrapDropdownCheckbox;
use ServiceBoiler\Repo\Filters\CheckboxFilter;

class MissionUserFilter extends CheckboxFilter
{
    use BootstrapDropdownCheckbox;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if($this->has($this->name()) && $this->filled($this->name())){
            $builder = $builder->whereHas('missionUsers', function ($query){
                $query->whereIn('user_id', $this->get($this->name()));
            });
            //$builder = $builder->where('quantity', '>', 0);
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
        return User::whereHas('missions')->get()->pluck('name', 'id')->toArray();
    }

    /**
     * @return string
     */
    function name(): string
    {
        return 'users';
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
        return "Сотрудники";
    }
}