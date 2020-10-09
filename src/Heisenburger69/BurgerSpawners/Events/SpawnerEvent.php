<?php

namespace Heisenburger69\BurgerSpawners\Events;

use Heisenburger69\BurgerSpawners\Main;
use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class SpawnerEvent extends PluginEvent
{
    /**
     * @var MobSpawnerTile
     */
    private $spawnerTile;
    /**
     * @var Player
     */
    private $player;

    public function __construct(Player $player, MobSpawnerTile $spawnerTile)
    {
        parent::__construct(Main::getInstance());
        $this->player = $player;
        $this->spawnerTile = $spawnerTile;
    }

    /**
     * @return MobSpawnerTile
     */
    public function getSpawnerTile(): MobSpawnerTile
    {
        return $this->spawnerTile;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }
}