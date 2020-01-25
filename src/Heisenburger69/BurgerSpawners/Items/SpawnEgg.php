<?php

namespace Heisenburger69\BurgerSpawners\Items;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\SpawnEgg as PMSpawnEgg;
use pocketmine\math\Vector3;
use pocketmine\Player;

class SpawnEgg extends PMSpawnEgg
{

    /**
     * @param Player $player
     * @param Block $block
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @return bool
     */
    public function onActivate(Player $player, Block $block, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if ($blockClicked instanceof SpawnerBlock) {
            return false;
        }
        $level = $player->getLevel();
        $nbt = Entity::createBaseNBT($block->add(1, 0, 1), null, lcg_value() * 360, 0);
        $entity = Entity::createEntity($this->meta, $level, $nbt);
        if ($entity instanceof Entity) {
            if (!$player->isCreative()) {
                $this->count--;
            }
            $entity->spawnToAll();
            return true;
        }
        return true;
    }
}