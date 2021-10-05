<?php

namespace ServiceBoiler\Prf\Site\Filters\Ticket;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter As BaseFilter;

class TicketSearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_ticket';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder = $builder->where(function ($query) use ($word) {
                            $query->orWhereRaw("LOWER(tickets.text) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tickets.consumer_name) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tickets.consumer_email) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tickets.consumer_phone) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tickets.consumer_company) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(tickets.locality) LIKE LOWER(?)", ["%{$word}%"]);
                            
                        });
                    }
                }
                
        }

        return $builder;
    }

    public function label()
    {
        return 'Поиск по тикету';
        
    }
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:300px;']);
    }

    
}