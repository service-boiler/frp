<?php

namespace ServiceBoiler\Prf\Site\Filters\Datasheet;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\FileType;

class DatasheetTypeSelectFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($type_id = $this->get($this->name()))) {
            $builder = $builder
            ->whereHas('file', function ($file) use ($type_id) {
                $file->where($this->column(), $type_id);
                })
            ->orWhere('type_id', $type_id);
        }

        return $builder;
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

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return FileType::query()
            ->where('group_id', 2)
            ->whereHas('files', function ($file) {
                $file->has('datasheets');
            })
            ->orWhereHas('datasheets', function ($query) {
                    $query->where('datasheets.active', 1)
                        ;
                })
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->prepend(trans('site::datasheet.type_defaults'), '')
            ->toArray();
    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::datasheet.type_id');
    }
}