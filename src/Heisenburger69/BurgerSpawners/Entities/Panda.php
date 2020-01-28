<?php


namespace Heisenburger69\BurgerSpawners\Entities;


use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Panda extends Animal
{

    public const NETWORK_ID = self::PANDA;

    public $width = 1.2;
    public $height = 1.2;

    public function getName(): string
    {
        return "Panda";
    }

    public function getDrops(): array
    {
        $item = Item::get(Item::SUGARCANE, 0, mt_rand(1, 3));
        return [$item];
    }
}