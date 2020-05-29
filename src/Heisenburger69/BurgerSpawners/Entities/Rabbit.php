<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\event\entity\{
    EntityDamageByEntityEvent, EntityDamageEvent
};
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\nbt\tag\{
    IntTag
};
use pocketmine\Player;
use function mt_rand;

class Rabbit extends Animal {

    public const NETWORK_ID = self::RABBIT;

    /** @var int */
    public const DATA_RABBIT_TYPE = 18;

    public const TAG_RABBIT_TYPE = "RabbitType";
    public $width = 0.4;
    public $height = 0.5;

    public function initEntity(): void{
        $type = $this->getRandomRabbitType();
        if(!$this->namedtag->hasTag(self::TAG_RABBIT_TYPE, IntTag::class)){
            $this->namedtag->setInt(self::TAG_RABBIT_TYPE, $type);
        }

        $this->setMaxHealth(3);
        $this->getDataPropertyManager()->setByte(self::DATA_RABBIT_TYPE, $type);
        parent::initEntity();
    }

    public function getRandomRabbitType(): int{
        $arr = [0, 1, 2, 3, 4, 5, 99];

        return $arr[array_rand($arr)];
    }

    public function getRabbitType(): int{
        return $this->namedtag->getInt(self::TAG_RABBIT_TYPE);
    }

    public function getName(): string{
        return "Rabbit";
    }

    /**
     * @param int $type
     */
    public function setRabbitType(int $type):void
    {
        $this->namedtag->setInt(self::TAG_RABBIT_TYPE, $type);
    }

    public function getDrops(): array{
        $lootingL = 1;
        $cause = $this->lastDamageCause;
        if($cause instanceof EntityDamageByEntityEvent){
            $damager = $cause->getDamager();
            if($damager instanceof Player){
            
                $looting = $damager->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
                if($looting !== null){
                    $lootingL = $looting->getLevel();
                }else{
                    $lootingL = 1;
                }
            }
        }
        $drops = [Item::get(Item::RABBIT_HIDE, 0, mt_rand(0, 1 * $lootingL))];
        if(mt_rand(1, 200) <= (5 + 2 * $lootingL)){
            $drops[] = Item::get(Item::RABBIT_FOOT, 0, 1 * $lootingL);
        }

        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}