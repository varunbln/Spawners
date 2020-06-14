<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use function mt_rand;

class MagmaCube extends Monster {

    public const NETWORK_ID = self::MAGMA_CUBE;

    public $width = 2.04;
    public $height = 2.04;

    public function getName(): string{
        return "Magma Cube";
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
        return [Item::get(Item::MAGMA_CREAM, 0, mt_rand(0, 1 * $lootingL))];
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 4);
    }

}