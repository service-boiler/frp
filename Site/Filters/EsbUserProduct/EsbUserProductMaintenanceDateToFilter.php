<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;

class EsbUserProductMaintenanceDateToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_mt_to';

    function apply($builder, RepositoryInterface $repository)
    {
         if ($this->canTrack() && $this->filled($this->search)) {
                        
         $builder = $builder->whereIn('id',EsbUserProduct::get()->filter(function($item) {
                        return $item->last_maintenance && $item->last_maintenance->date <= date('Y-m-d', strtotime($this->get($this->search)));
                        })->pluck('id'));
        }
        return $builder;
    }
    
    
    protected function placeholder()
    {
        return trans('site::messages.date_to');
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
        return '<=';
    }


}