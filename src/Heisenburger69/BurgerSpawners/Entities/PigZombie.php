<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use function mt_rand;

class PigZombie extends Monster {

    public const NETWORK_ID = self::ZOMBIE_PIGMAN;

    public $width = 0.6;
    public $height = 1.95;
    public function getName(): string{
        return "Zombie Pigman";
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
        $drops = [
            Item::get(Item::GOLD_NUGGET, 0, mt_rand(0, 1 * $lootingL)),
            Item::get(Item::ROTTEN_FLESH, 0, mt_rand(0, 1 * $lootingL)),
        ];

        if(mt_rand(1, 200) <= 7){
            $drops[] = Item::get(Item::GOLD_INGOT, 0, 1 * $lootingL);
        }
        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}