<?php

namespace ServiceBoiler\Prf\Site\Filters\Message;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ScSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_sc';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    $builder = $builder->where(function ($query) use ($words) {
                        foreach ($words as $word) {
                            //$query->orWhere(function($query) use ($word){
                            $query->orWhereHas('user', function ($query) use ($word) {
                                $query->where(function ($query) use ($word) {
                                    foreach ($this->columns() as $column) {
                                        $query->orWhereRaw("LOWER({$column}) LIKE LOWER(?)", ["%{$word}%"]);
                                    }
                                });
                            });
                            $query->orWhereHas('receiver', function ($query) use ($word) {
                                $query->where(function ($query) use ($word) {
                                    foreach ($this->columns() as $column) {
                                        $query->orWhereRaw("LOWER({$column}) LIKE LOWER(?)", ["%{$word}%"]);
                                    }
                                });
                            });
                            //});

                        }
                    });

                }
            }
        }
        //dump($builder->getBindings());
        //dd($builder->toSql());

        return $builder;
    }

    protected function columns()
    {
        return [
            'users.name',
            'users.email',
        ];
    }

    public function label()
    {
        return trans('site::message.placeholder.search_sc');
    }

    public function tooltip()
    {
        return trans('site::message.help.search_sc');
    }

}