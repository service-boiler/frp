<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Equipment;

class PartEquipmentFilter extends WhereFilter
{

    use BootstrapSelect;

    protected $render = true;

    /**
     * @param $builder
     * @param RepositoryInterface $repository
     * @return mixed
     */
    function apply($builder, RepositoryInterface $repository)
    {

        if ($this->canTrack() && $this->filled($this->name())) {

            $equipment_id = $this->get($this->name());
            $builder = $builder->whereHas('relations', function ($query) use ($equipment_id) {
                $query->where('equipment_id', $equipment_id);
            });
        }

        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'equipment_id';
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        $options = Equipment::query()
            ->where(config('site.check_field'), 1)
            ->whereHas('products', function ($query) {
                $query->where('products.enabled', 1);
                $query->whereHas('details', function ($query) {
                    $query->where('enabled', 1)
                        ->where(config('site.check_field'), 1)
                        ->whereNull('equipment_id');
                });
            })->orderBy('name')
            ->pluck('name', 'id')
            ->prepend(trans('site::messages.select_from_list'), '');

        return $options->toArray();
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'products.equipment_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::product.equipment_id');
    }

}