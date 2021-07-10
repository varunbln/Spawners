<?php

namespace Heisenburger69\BurgerSpawners\events;

use Heisenburger69\BurgerSpawners\tiles\MobSpawnerTile;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class SpawnerUnstackEvent extends SpawnerEvent implements Cancellable
{
    /** @var int */
    public $count;

    public function __construct(Player $player, MobSpawnerTile $spawnerTile, int $count)
    {
        $this->count = $count;
        parent::__construct($player, $spawnerTile);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}