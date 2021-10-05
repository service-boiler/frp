<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter As BaseFilter;

class TenderAddressSearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_address';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder = $builder->where(function ($query) use ($word) {
                            $query->orWhereRaw("LOWER(tenders.address_name) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.address) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.address_addon) LIKE LOWER(?)", ["%{$word}%"]);
                            
                        });
                    }
                }
                
        }

        return $builder;
    }

    public function label()
    {
        return 'Адрес, объект';
    }

    
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:300px;']);
    }

}