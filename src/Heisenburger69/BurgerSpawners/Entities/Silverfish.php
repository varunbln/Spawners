<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;

class Silverfish extends Monster {

    public const NETWORK_ID = self::SILVERFISH;

    public $height = 0.3;
    public $width = 0.4;

    public function getName(): string{
        return "Silverfish";
    }
}