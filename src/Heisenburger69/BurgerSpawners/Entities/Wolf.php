<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Wolf extends Animal {

    public const NETWORK_ID = self::WOLF;

    public $width = 0.6;
    public $height = 0.85;

    public function getName(): string{
        return "Wolf";
    }

    public function getDrops(): array
    {
        return [Item::get(Item::BONE, 0, mt_rand(0, 3))];
    }
}