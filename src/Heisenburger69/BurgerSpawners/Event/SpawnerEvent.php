<?php

namespace Heisenburger69\BurgerSpawners\Events;

use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use pocketmine\event\Event;

class SpawnerEvent extends Event
{
    /**
     * @var MobSpawnerTile
     */
    private $spawnerTile;

    public function __construct(MobSpawnerTile $spawnerTile)
    {
        $this->spawnerTile = $spawnerTile;
    }
}