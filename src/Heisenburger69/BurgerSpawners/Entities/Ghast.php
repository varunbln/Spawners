<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use function mt_rand;

class Ghast extends Monster {

    public const NETWORK_ID = self::GHAST;

    public $width = 6;
    /** @var int */
    public $length = 6;
    public $height = 6;

    public function getName(): string{
        return "Ghast";
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
        if(mt_rand(0, 1) == 1){
            $drops = [
                Item::get(Item::GUNPOWDER, 0, mt_rand(0, 1 * $lootingL)),
            ];
        }else{
            $drops = [
                Item::get(Item::GHAST_TEAR, 0, 1 * $lootingL),
            ];
        }
        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}