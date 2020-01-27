<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\item\Item;

class WitherSkeleton extends Skeleton {

    public const NETWORK_ID = self::WITHER_SKELETON;

    public $width = 0.7;
    public $height = 2.4;

    public function getName(): string{
        return "Wither Skeleton";
    }

    public function initEntity(): void{
        $this->setMaxHealth(20);
        parent::initEntity();
    }

    public function getDrops(): array{
        return [
            Item::get(Item::COAL, 0, mt_rand(0, 1)),
            Item::get(Item::BONE, 0, mt_rand(0, 2)),
        ];
    }
}