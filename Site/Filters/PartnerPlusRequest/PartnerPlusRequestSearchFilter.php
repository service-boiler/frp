<?php

namespace ServiceBoiler\Prf\Site\Filters\PartnerPlusRequest;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter As BaseFilter;

class PartnerPlusRequestSearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_name';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder = $builder->where(function ($query) use ($word) {
                            $query->whereHas('partner', function ($q) use ($word) {
                                $q->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$word}%"])
                                ->orWhereRaw("LOWER(name_for_site) LIKE LOWER(?)", ["%{$word}%"]);
                            })->orWhereHas('distributor', function ($q) use ($word) {
                                $q->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$word}%"]);
                            })->orWhereHas('address', function ($q) use ($word) {
                                $q->whereRaw("LOWER(full) LIKE LOWER(?)", ["%{$word}%"]);
                            });
                            
                            
                        });
                    }
                }
                
        }

        return $builder;
    }

    public function label()
    {
        return 'Партнер, дистрибьютор, адрес';
    }

    
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:600px;']);
    }

}