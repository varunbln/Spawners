<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use function mt_rand;

class Silverfish extends Monster {

    public const NETWORK_ID = self::SILVERFISH;

    public $height = 0.3;
    public $width = 0.4;

    public function getName(): string{
        return "Silverfish";
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}