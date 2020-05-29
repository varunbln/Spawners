<?php


namespace Heisenburger69\BurgerSpawners\Entities;


use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use function mt_rand;


class CaveSpider extends Monster {

    public const NETWORK_ID = self::CAVE_SPIDER;

    public $width = 1;
    /** @var int */
    public $length = 1;
    public $height = 0.5;

    public function getName(): string{
        return "Cave Spider";
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
            Item::get(Item::STRING, 0, mt_rand(0, 2 * $lootingL)),
        ];

        if(mt_rand(1, 3) == 2){
            $lastDamage = $this->getLastDamageCause();
            if($lastDamage instanceof EntityDamageByEntityEvent){
                $ent = $lastDamage->getDamager();
                if($ent instanceof Player){
                    $drops[] = Item::get(Item::SPIDER_EYE, 0, 1 * $lootingL);
                }
            }
        }

        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}