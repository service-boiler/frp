<?php

namespace ServiceBoiler\Prf\Site\Filters\Contact;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\ContactType;

class ContactTypeSelectFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return ContactType::query()
            ->has('contacts')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->map(function ($item, $key) {
                return str_limit($item, config('site.name_limit', 25));
            })
            ->prepend(trans('site::messages.select_no_matter'), '')
            ->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'type';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'type_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::contact.type_id');
    }

}