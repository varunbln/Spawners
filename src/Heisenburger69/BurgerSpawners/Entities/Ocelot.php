<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;

class Ocelot extends Animal {

    public const NETWORK_ID = self::OCELOT;

    public $width = 0.6;
    public $height = 0.7;

    public function getName(): string{
        return "Ocelot";
    }

}