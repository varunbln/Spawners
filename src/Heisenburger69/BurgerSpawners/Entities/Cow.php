<?php


namespace Heisenburger69\BurgerSpawners\Entities;


use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Cow extends Animal {

    public const NETWORK_ID = self::COW;

    public $width = 0.9;
    public $height = 1.3;

    public function getName(): string{
        return "Cow";
    }

    public function getDrops(): array{
        return [
            Item::get(Item::RAW_BEEF, 0, mt_rand(1, 3)),
            Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
        ];
    }
}