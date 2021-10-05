<?php

namespace ServiceBoiler\Prf\Site\Filters\News;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class PublishedSelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'published';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'published';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::news.published');
    }
}