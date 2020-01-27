<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Vindicator extends Monster {

    public const NETWORK_ID = self::VINDICATOR;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string{
        return "Vindicator";
    }

    public function initEntity(): void{
        $this->setMaxHealth(24);
        parent::initEntity();
    }

    public function getDrops(): array{
        return [
            Item::get(Item::EMERALD, 0, mt_rand(0, 1)),
        ];
    }
}