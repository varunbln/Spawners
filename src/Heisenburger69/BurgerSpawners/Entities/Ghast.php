<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Ghast extends Monster {

    public const NETWORK_ID = self::GHAST;

    public $width = 6;
    public $length = (int) 6;
    public $height = 6;

    public function getName(): string{
        return "Ghast";
    }

    public function getDrops(): array{
        if(mt_rand(0, 1) == 1){
            $drops = [
                Item::get(Item::GUNPOWDER, 0, mt_rand(0, 1)),
            ];
        }else{
            $drops = [
                Item::get(Item::GHAST_TEAR, 0, 1),
            ];
        }
        return $drops;
    }
}