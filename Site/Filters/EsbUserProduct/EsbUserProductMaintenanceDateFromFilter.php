<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;

class EsbUserProductMaintenanceDateFromFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_mt_form';

    function apply($builder, RepositoryInterface $repository)
    {
         if ($this->canTrack() && $this->filled($this->search)) {
                        
         $builder = $builder->whereIn('id',EsbUserProduct::get()->filter(function($item) {
                        return $item->last_maintenance && $item->last_maintenance->date >= date('Y-m-d', strtotime($this->get($this->search)));
                        })->pluck('id'));
        }
        return $builder;
    }
    
    
    protected function placeholder()
    {
        return trans('site::messages.date_from');
    }

    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:100px;']);
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'esb_product_maintenances.date';
    }

    protected function operator()
    {
        return '>=';
    }

    public function label()
    {
        return trans('site::user.esb_user_product.last_maintenance');
    }

}