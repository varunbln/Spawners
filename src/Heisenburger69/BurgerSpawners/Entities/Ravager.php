<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use Heisenburger69\BurgerSpawners\Pocketmine\AddActorPacket;
use pocketmine\entity\Living;
use pocketmine\item\Item;
use pocketmine\Player;

class Ravager extends Living
{
    public const NETWORK_ID = 59;

    public $width = 1.975;
    public $height = 2.2;

    public function getName(): string
    {
        return "Ravager";
    }

    /**
     * @param Player $player
     */
    protected function sendSpawnPacket(Player $player): void
    {
        $pk = new AddActorPacket();
        $pk->entityRuntimeId = $this->getId();
        $pk->type = "minecraft:ravager";
        $pk->position = $this->asVector3();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->headYaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->attributes = $this->attributeMap->getAll();
        $pk->metadata = $this->propertyManager->getAll();

        $player->dataPacket($pk);
    }

    public function getDrops(): array
    {
        //Ravager drops aren't affected by Looting
        $drops = [Item::get(Item::SADDLE, 0, 1)];
        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return 20;
    }
}