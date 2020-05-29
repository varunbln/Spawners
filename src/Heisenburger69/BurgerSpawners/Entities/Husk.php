<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Zombie;
use function mt_rand;

class Husk extends Zombie {

    public const NETWORK_ID = self::HUSK;

    public function getName(): string{
        return "Husk";
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}