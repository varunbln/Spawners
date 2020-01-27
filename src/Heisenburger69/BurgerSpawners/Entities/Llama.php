<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\{Animal, Entity};
use pocketmine\item\Item;

class Llama extends Animal
{
    public const NETWORK_ID = self::LLAMA;

    const WHITE = 1;

    public $width = 0.9;
    public $height = 1.87;

    public function getName(): string
    {
        return "Llama";
    }

    public function initEntity(): void
    {
        $this->setMaxHealth(30);
        $this->getDataPropertyManager()->setInt(Entity::DATA_VARIANT, rand(0, 3));
        parent::initEntity();
    }

    public function getDrops(): array
    {
        return [
            Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
        ];
    }
}