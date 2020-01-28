<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

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
        $lootingL = 1;
        $cause = $this->lastDamageCause;
        if($cause instanceof EntityDamageByEntityEvent){
            $dmg = $cause->getDamager();
            if($dmg instanceof Player){
                /** @var Enchantment $looting */
                $looting = $dmg->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
                if($looting !== null){
                    $lootingL = $looting->getLevel();
                }else{
                    $lootingL = 1;
            }
            }
        }
        return [
            Item::get(Item::COAL, 0, mt_rand(0, 1 * $lootingL)),
            Item::get(Item::BONE, 0, mt_rand(0, 2 * $lootingL)),
        ];
    }
}