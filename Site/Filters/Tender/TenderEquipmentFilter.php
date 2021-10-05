<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Equipment;

class TenderEquipmentFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($equipment_id = $this->get($this->name()))) {
            $builder = $builder->whereHas('products', function ($query) use ($equipment_id) {
                $query->where(DB::raw($this->column()), $this->operator(), $equipment_id);

            });
        }
        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'equipment';
    }

    /**
     * @return string
     */
    public function column(): string
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
        return Equipment::whereHas('products', function ($query) {
                $query->has('tenders');
            
        })->orderBy('name')
            ->pluck('name', 'id')
            ->prepend(trans('site::messages.select_no_matter'), '')
            ->toArray();
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