<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class CustomerSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_customer';

	/**
	 * @var array
	 */
	protected $restricted = [
		"'", '"', '!', '#', '$', '%', '^', '&', '*', '?', '=', '+', ':',
		'|', '`', '№', '~', '!', '<', '>', '{', '}', '[', ']', '\\', '/'
	];

    public function label()
    {
        return trans('site::user.placeholder.search');
    }

    protected function columns()
    {
        return [
            'customers.name',
            'customers.email',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');

        return $attributes;
    }

}