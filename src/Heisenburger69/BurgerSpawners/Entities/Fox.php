<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use Heisenburger69\BurgerSpawners\Pocketmine\AddActorPacket;

use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;
use function mt_rand;

class Fox extends Living
{

    public const NETWORK_ID = 121;

    public $width = 0.7;
    public $height = 0.6;

    public function getName(): string
    {
        return "Fox";
    }

    protected function sendSpawnPacket(Player $player): void
    {
        $pk = new AddActorPacket();
        $pk->entityRuntimeId = $this->getId();
        $pk->type = "minecraft:fox";
        $pk->position = $this->asVector3();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->headYaw = $this->yaw; //TODO
        $pk->pitch = $this->pitch;
        $pk->attributes = $this->attributeMap->getAll();
        $pk->metadata = $this->propertyManager->getAll();

        $player->dataPacket($pk);
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
        $drops = [Item::get(Item::RABBIT_HIDE, 0, mt_rand(0, 1 * $lootingL))];
        if(mt_rand(1, 200) <= (5 + 2 * $lootingL)){
            $drops[] = Item::get(Item::RABBIT_FOOT, 0, 1 * $lootingL);
        }
        if(mt_rand(1, 200) <= (5 + 2 * $lootingL)){
            $drops[] = Item::get(Item::EMERALD, 0, 1 * $lootingL);
        }

        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}