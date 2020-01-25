<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;

class IronGolem extends Animal
{

    public const NETWORK_ID = self::IRON_GOLEM;

    public $width = 1.4;
    public $height = 2.7;

    public function getName(): string
    {
        return "Iron Golem";
    }
}