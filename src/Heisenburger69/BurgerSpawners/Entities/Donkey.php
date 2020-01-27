<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Donkey extends Animal
{

    public const NETWORK_ID = self::DONKEY;

    public $width = 0.3;
    public $length = 0.9;
    public $height = 0;

    public function getName(): string
    {
        return "Donkey";
    }

    public function getDrops(): array
    {
        return $drops = [
            Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
        ];
    }
}