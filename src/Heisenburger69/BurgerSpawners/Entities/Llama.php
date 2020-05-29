<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\{Animal, Entity};
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use function mt_rand;

class Llama extends Animal
{
    public const NETWORK_ID = self::LLAMA;

    const WHITE = 1;

    public $width = 0.9;
    public $height = 1.87;

    public function getName(): string
    {
        return "Llama";
    }

    public function initEntity(): void
    {
        $this->setMaxHealth(30);
        $this->getDataPropertyManager()->setInt(Entity::DATA_VARIANT, rand(0, 3));
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
            Item::get(Item::LEATHER, 0, mt_rand(0, 2 * $lootingL)),
        ];
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}