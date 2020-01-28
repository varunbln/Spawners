<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;

class Mooshroom extends Animal {

    public const NETWORK_ID = self::MOOSHROOM;

    public $width = 0.9;
    public $height = 1.4;

    public function getName(): string{
        return "Mooshroom";
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
        return [
            Item::get(Item::RAW_BEEF, 0, mt_rand(1, 3 * $lootingL)),
            Item::get(Item::LEATHER, 0, mt_rand(0, 2 * $lootingL)),
        ];
    }
}