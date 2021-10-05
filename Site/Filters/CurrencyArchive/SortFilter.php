<?php

namespace ServiceBoiler\Prf\Site\Filters\CurrencyArchive;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'currency_archives.date' => 'DESC'
        ];
    }

}