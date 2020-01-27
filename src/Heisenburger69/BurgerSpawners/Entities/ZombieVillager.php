<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Zombie;

class ZombieVillager extends Zombie
{

    public const NETWORK_ID = self::ZOMBIE_VILLAGER;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string
    {
        return "Zombie Villager";
    }

    public function initEntity(): void
    {
        $this->setMaxHealth(20);
        parent::initEntity();
    }
}