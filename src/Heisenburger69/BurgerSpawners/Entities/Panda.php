<?php


namespace Heisenburger69\BurgerSpawners\Entities;


use pocketmine\entity\Animal;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use function mt_rand;

class Panda extends Animal
{

    public const NETWORK_ID = self::PANDA;

    public $width = 1.2;
    public $height = 1.2;
  

    public function getName(): string
    {
        return "Panda";
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
        $item = Item::get(Item::SUGARCANE, 0, mt_rand(1, 3 * $lootingL));
        return [$item];
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}