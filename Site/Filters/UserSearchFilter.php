<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class UserSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_user';

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
            'users.name',
            'users.name_for_site',
            'users.email',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');

        return $attributes;
    }

}