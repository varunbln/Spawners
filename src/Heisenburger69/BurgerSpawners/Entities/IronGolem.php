<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

class IronGolem extends Animal
{

    public const NETWORK_ID = self::IRON_GOLEM;

    public $width = 1.4;
    public $height = 2.7;

    public function getName(): string
    {
        return "Iron Golem";
    }

    public function initEntity(): void{
        $this->setMaxHealth(100);
        parent::initEntity();
    }

    public function getDrops(): array
    {
        $iron = Item::get(Item::IRON_INGOT, 0, mt_rand(1, 2));
        $rose = Item::get(Item::RED_FLOWER, 0, 1);
        if(mt_rand(0, 5) === 0) {
            return [$iron, $rose];
        }
        return [$iron];
    }
}