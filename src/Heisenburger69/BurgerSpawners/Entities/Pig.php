<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Pig extends Animal {

    public const NETWORK_ID = self::PIG;

    public $width = 0.9;
    public $height = 0.9;

    public function getName(): string{
        return "Pig";
    }

    public function getDrops(): array{
        return [
            Item::get(Item::RAW_PORKCHOP, 0, mt_rand(1, 3)),
        ];
    }
}