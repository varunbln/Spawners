<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Evoker extends Monster {

    public const NETWORK_ID = self::EVOCATION_ILLAGER;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string{
        return "Evoker";
    }

    public function getDrops(): array{
        return [
            Item::get(Item::EMERALD, 0, mt_rand(0, 1))
        ];
    }
}