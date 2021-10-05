<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use ServiceBoiler\Prf\Site\Models\User;

class ActMountingCreateEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var User
     */
    public $user;
    /**
     * @var Collection
     */
    public $acts;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Collection $acts
     */
    public function __construct(User $user, Collection $acts)
    {
        $this->user = $user;
        $this->acts = $acts;
    }
}
