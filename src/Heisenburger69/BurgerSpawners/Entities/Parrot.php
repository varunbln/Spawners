<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use function mt_rand;

class Parrot extends Animal {

    public const NETWORK_ID = self::PARROT;

    public $height = 0.9;
    public $width = 0.5;

    public function getName(): string{
        return "Parrot";
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
        return [Item::get(Item::FEATHER, 0, mt_rand(1, 2 * $lootingL))];
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}