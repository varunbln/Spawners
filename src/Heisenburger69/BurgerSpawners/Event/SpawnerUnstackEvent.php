<?php

namespace Heisenburger69\BurgerSpawners\Events;

use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;

class SpawnerUnstackEvent extends SpawnerEvent
{
    /** @var int */
    public $count;

    public function __construct(MobSpawnerTile $spawnerTile, int $count)
    {
        $this->count = $count;
        parent::__construct($spawnerTile);
    }
}