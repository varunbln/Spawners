<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Enderman extends Monster {

    public const NETWORK_ID = self::ENDERMAN;

    public $width = 0.3;
    public $length = (float) 0.9;
    public $height = 1.8;

    public function getName(): string{
        return "Enderman";
    }

    public function getDrops(): array{
        return [
            Item::get(Item::ENDER_PEARL, 0, mt_rand(0, 1)),
        ];
    }
}