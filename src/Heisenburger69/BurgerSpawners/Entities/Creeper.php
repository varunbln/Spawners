<?php


namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Creeper extends Monster
{

    public const NETWORK_ID = self::CREEPER;

    public $height = 1.7;
    public $width = 0.6;


    public function getName(): string
    {
        return "Creeper";
    }

    public function getDrops(): array
    {
        if (mt_rand(1, 10) < 3) {
            return [Item::get(Item::GUNPOWDER, 0, 1)];
        }

        return [];
    }

}