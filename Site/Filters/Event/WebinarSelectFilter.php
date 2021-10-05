<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\EvenType;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\FerroliSingleSelect;

class WebinarSelectFilter extends BooleanFilter
{
    use FerroliSingleSelect;

    protected $render = true;

    
function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && $this->filled($this->name())) {
            switch ($this->get($this->name())) {
                case "1":
                        $builder->whereHas('type', function ($query) {
                            $query->where('is_webinar', '=', '1');
                        });
                    break;
                case "0":
                    $builder->whereHas('type', function ($query) {
                        $query->where('is_webinar', '=', '0');
                    });
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
        return 'is_webinar';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'is_webinar';

    }

    public function defaults(): array
    {
        return [];
    }
	
	    public function options(): array
    {
        return [
            ''  => trans('site::messages.webinar.select_no_matter'),
            '1' => trans('site::messages.webinar.yes'),
            '0' => trans('site::messages.webinar.no'),
        ];
    }


    public function label()
    {
        return trans('site::event.is_webinar');
    }
}