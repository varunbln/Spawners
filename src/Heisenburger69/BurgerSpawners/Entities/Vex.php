<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;

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
}