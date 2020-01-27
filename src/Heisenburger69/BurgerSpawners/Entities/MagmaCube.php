<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class MagmaCube extends Monster {

    public const NETWORK_ID = self::MAGMA_CUBE;

    public function getName(): string{
        return "Magma Cube";
    }

    public function getDrops(): array
    {
        return [Item::get(Item::MAGMA_CREAM, 0, mt_rand(0, 1))];
    }

}