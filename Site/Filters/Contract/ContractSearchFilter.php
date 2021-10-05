<?php

namespace ServiceBoiler\Prf\Site\Filters\Contract;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class ContractSearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search';

    public function label()
    {
        return trans('site::contract.placeholder.search');
    }

    public function tooltip()
    {
        return trans('site::contract.help.search');
    }

    protected function columns()
    {
        return [
            'contracts.number',
            'contracts.territory',
            'contracts.signer',
        ];
    }

}