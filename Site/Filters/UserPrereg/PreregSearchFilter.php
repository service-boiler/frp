<?php

namespace ServiceBoiler\Prf\Site\Filters\UserPrereg;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class PreregSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_prereg';

	/**
	 * @var array
	 */
	protected $restricted = [
		"'", '"', '!', '#', '$', '%', '^', '&', '*', '?', '=', '+', ':',
		'|', '`', '№', '~', '!', '<', '>', '{', '}', '[', ']', '\\', '/'
	];

    
    
    public function label()
    {
        return 'Фамилия, компания, email или телефон';
    }

    protected function columns()
    {
        return [
            'user_preregs.lastname',
            'user_preregs.firstname',
            'user_preregs.phone',
            'user_preregs.parent_name',
            'user_preregs.email',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 308px;');

        return $attributes;
    }

}