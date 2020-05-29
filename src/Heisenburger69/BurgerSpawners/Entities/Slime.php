<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use function mt_rand;

class Slime extends Living {

    public const NETWORK_ID = self::SLIME;

    public $width = 2.04;
    public $height = 2.04;

    public function getName(): string{
        return "Slime";
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
        $drops = [Item::get(Item::SLIMEBALL, 0, 1 * $lootingL)];
            if(mt_rand(0, 199) < 5){
                switch(mt_rand(0, 2)){
                    case 0:
                        $drops[] = Item::get(Item::IRON_INGOT, 0, 1 * $lootingL);
                        break;
                    case 1:
                        $drops[] = Item::get(Item::CARROT, 0, 1 * $lootingL);
                        break;
                    case 2:
                        $drops[] = Item::get(Item::POTATO, 0, 1 * $lootingL);
                        break;
                }
            }

        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 4);
    }
}