<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\block\Block;
use pocketmine\block\SnowLayer;
use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\Shears;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\Player;

class SnowGolem extends Monster {

    public const NETWORK_ID = self::SNOW_GOLEM;
    public const TAG_PUMPKIN = "Pumpkin";

    public $width = 0.7;
    public $height = 1.9;

    public function getName(): string{
        return "Snow Golem";
    }

    public function initEntity(): void{
        $this->setMaxHealth(4);
        $this->setHealth(4);
        parent::initEntity();
    }

    public function getDrops(): array{
        return [Item::get(Item::SNOWBALL, 0, mt_rand(0, 15))];
    }
}