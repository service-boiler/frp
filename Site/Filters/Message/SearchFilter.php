<?php

namespace ServiceBoiler\Prf\Site\Filters\Message;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class SearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_message';

    protected function columns()
    {
        return [
            'messages.text',
        ];
    }

    public function label()
    {
        return trans('site::message.placeholder.search_text');
    }

    public function tooltip()
    {
        return trans('site::message.help.search_text');
    }

}