<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;

class Endermite extends Monster {

    public const NETWORK_ID = self::ENDERMITE;

    public $height = 0.3;
    public $width = 0.4;

    public function getName(): string{
        return "Endermite";
    }

    public function getXpDropAmount(): int
    {
        return 3;
    }
}