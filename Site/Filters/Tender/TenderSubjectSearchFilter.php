<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter As BaseFilter;

class TenderSubjectSearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_subject';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder = $builder->where(function ($query) use ($word) {
                            $query->orWhereRaw("LOWER(tenders.investor_name) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.customer) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.gen_contractor) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.gen_designer) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.designers) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.contractors) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tenders.picker) LIKE LOWER(?)", ["%{$word}%"]);
                            
                        });
                    }
                }
                
        }

        return $builder;
    }

    public function label()
    {
        return 'Субъекты (подрядчики и т.п.)';
        
    }
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:300px;']);
    }

    
}