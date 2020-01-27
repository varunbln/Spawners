<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Zombie;

class Husk extends Zombie {

    public const NETWORK_ID = self::HUSK;

    public function getName(): string{
        return "Husk";
    }
}