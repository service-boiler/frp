<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter As BaseFilter;

class RegisterUserSearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_user';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder = $builder->where(function ($query) use ($word) {
                            $query->orWhereRaw("LOWER(users.name) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(users.name_for_site) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereRaw("LOWER(users.email) LIKE LOWER(?)", ["%{$word}%"]);
                            $query->orWhereHas('contragents', function ($query) use ($word) {
                                    $query->where(function ($query) use ($word) {
                                                $query->orWhereRaw("LOWER(name_short) LIKE LOWER(?)", ["%{$word}%"]);
                                                $query->orWhereRaw("LOWER(name) LIKE LOWER(?)", ["%{$word}%"]);
                                                $query->orWhereRaw("LOWER(inn) LIKE LOWER(?)", ["%{$word}%"]);
                                            });
                                        });
                            $query->orWhereHas('addresses', function ($query) use ($word) {
                                    $query->where(function ($query) use ($word) {
                                                $query->orWhereRaw("LOWER(name) LIKE LOWER(?)", ["%{$word}%"]);
                                               
                                            });
                                        });
                            
                        });
                    }
                }
                
        }

        return $builder;
    }

    public function label()
    {
        return trans('site::product.search_placeholder');
    }

    
}