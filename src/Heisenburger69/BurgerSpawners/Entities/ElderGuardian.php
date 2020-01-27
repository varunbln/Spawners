<?php


namespace Heisenburger69\BurgerSpawners\Entities;


use pocketmine\entity\Monster;
use pocketmine\item\Item;

class ElderGuardian extends Monster {

    public const NETWORK_ID = self::ELDER_GUARDIAN;

    public $width = 1.9975;
    public $height = 1.9975;

    public function getName(): string{
        return "Elder Guardian";
    }

    public function getDrops(): array{
        return [
            Item::get(Item::PRISMARINE_CRYSTALS, 0, mt_rand(0, 1)),
            Item::get(Item::PRISMARINE_SHARD, 0, mt_rand(0, 2)),
        ];
    }
}