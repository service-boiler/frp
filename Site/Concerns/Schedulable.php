<?php

namespace ServiceBoiler\Prf\Site\Concerns;

use ServiceBoiler\Prf\Site\Models\Schedule;

trait Schedulable
{
    /**
     * Проверить возможность создания синхронизации
     * @return bool
     */
    public function can_schedule()
    {
        return $this->schedules()->count() == 0 || $this->schedules()->latest()->first()->getAttribute('status') == 2;
    }

    /**
     * Расписание синхронизаций с 1С
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}