<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Guardian extends Monster
{

    public const NETWORK_ID = self::GUARDIAN;

    public $width = 0.85;
    public $height = 0.85;

    public function getName(): string
    {
        return "Guardian";
    }

    public function initEntity(): void
    {
        $this->setMaxHealth(30);
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
        return [
            Item::get(Item::RAW_FISH, 0, mt_rand(1, 2 * $lootingL)),
            Item::get(Item::PRISMARINE_SHARD, 0, mt_rand(0, 1 * $lootingL)),
        ];
    }

    public function getXpDropAmount(): int
    {
        return 10;
    }
}