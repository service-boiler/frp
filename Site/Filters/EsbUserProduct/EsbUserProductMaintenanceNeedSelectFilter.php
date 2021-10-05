<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use Carbon\Carbon;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;

class EsbUserProductMaintenanceNeedSelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
      
        
        if ($this->canTrack() && $this->filled($this->name())) {
            switch ($this->get($this->name())) {
                case "1":
                    $builder = $builder->whereIn('id',EsbUserProduct::get()->filter(function($item) {
                        return $item->next_maintenance <= Carbon::now()->addMonth(2)->format('Y-m-d') && $item->next_maintenance >= Carbon::now()->subMonth(2)->format('Y-m-d');
                        })->pluck('id'))
                        ->orWhereHas('visits', function ($q) {
                            $q->whereIn('status_id',[1,2,3,5])->where('date_planned','>',Carbon::now()->subMonth(1)->format('Y-m-d'));
                        })
                        ;
                    break;
                case "0":
                    $builder = $builder->whereIn('id',EsbUserProduct::get()->filter(function($item) {
                        return $item->next_maintenance > Carbon::now()->addMonth(2)->format('Y-m-d') && $item->next_maintenance < Carbon::now()->subMonth(1)->format('Y-m-d');
                        })->pluck('id'));
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
        return 'next_maintenance_need';
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
        return trans('site::user.esb_user_product.next_maintenance_need');
    }
    
    public function tooltip()
    {
         return trans('site::user.esb_user_product.next_maintenance_help');
    }

}
