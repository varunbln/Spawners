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
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

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
        $lootingL = 1;
        $cause = $this->lastDamageCause;
        if($cause instanceof EntityDamageByEntityEvent){
            $dmg = $cause->getDamager();
            if($dmg instanceof Player){
              
                $looting = $dmg->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
                if($looting !== null){
                    $lootingL = $looting->getLevel();
                }else{
                    $lootingL = 1;
            }
            }
        }
        return [Item::get(Item::SNOWBALL, 0, mt_rand(0, 15 * $lootingL))];
    }
}