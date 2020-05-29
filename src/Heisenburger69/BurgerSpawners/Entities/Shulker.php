<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\{
    Entity, Monster
};
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use function mt_rand;

class Shulker extends Monster {

    public const NETWORK_ID = self::SHULKER;

    public $width = 1;
    public $height = 1;

    public function getName(): string{
        return "Shulker";
    }

    public function initEntity(): void{
        $this->setMaxHealth(30);
        $this->getDataPropertyManager()->setInt(Entity::DATA_VARIANT, mt_rand(0, 15)); // TODO: Implement COLORS correctly
        parent::initEntity();
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
        return [Item::get(Item::SHULKER_SHELL, 0, mt_rand(0, 1 * $lootingL))];
    }

    public function knockBack(Entity $attacker, float $damage, float $x, float $z, float $base = 0.4): void{
        return;
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}