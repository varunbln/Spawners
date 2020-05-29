<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Animal;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;
use function mt_rand;

class Sheep extends Animal {

    public const NETWORK_ID = self::SHEEP;

    public $width = 0.9;
    public $height = 1.3;

    public function getName(): string{
        return "Sheep";
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
        return [
                Item::get(Item::WOOL, mt_rand(0, 15), 1 * $lootingL), //TODO: Check proper color
                Item::get(Item::RAW_MUTTON, 0, mt_rand(1, 2 * $lootingL)),
            ];
        }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}
