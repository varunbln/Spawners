<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

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
        $iron = Item::get(Item::IRON_INGOT, 0, mt_rand(1, 2 * $lootingL));
        $rose = Item::get(Item::RED_FLOWER, 0, 1 * $lootingL);
        if(mt_rand(0, 5) === 0) {
            return [$iron, $rose];
        }
        return [$iron];
    }
}