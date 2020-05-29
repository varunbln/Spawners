<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use function mt_rand;

class Vex extends Monster {

    public const NETWORK_ID = self::VEX;

    public $width = 0.4;
    public $height = 0.8;

    public function getName(): string{
        return "Vex";
    }

    public function initEntity(): void{
        $this->setMaxHealth(14);
        parent::initEntity();
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}