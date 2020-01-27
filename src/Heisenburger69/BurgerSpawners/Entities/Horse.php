<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Horse extends Animal
{

    public $width = 2;
    public $height = 3;

    public const NETWORK_ID = self::HORSE;

    public function getName(): string
    {
        return "Horse";
    }

    public function getDrops(): array
    {
        return $drops = [
            Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
        ];
    }
}