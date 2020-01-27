<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Guardian extends Monster
{

    public const NETWORK_ID = self::GUARDIAN;

    public $width = 0.85;
    public $height = 0.85;

    public function getName(): string
    {
        return "Guardian";
    }

    public function initEntity(): void
    {
        $this->setMaxHealth(30);
        parent::initEntity();
    }

    public function getDrops(): array
    {
        return [
            Item::get(Item::RAW_FISH, 0, mt_rand(1, 2)),
            Item::get(Item::PRISMARINE_SHARD, 0, mt_rand(0, 1)),
        ];
    }
}