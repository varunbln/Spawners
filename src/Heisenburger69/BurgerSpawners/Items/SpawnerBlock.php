<?php

namespace Heisenburger69\BurgerSpawners\Items;

use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\Utilities\ConfigManager;
use Heisenburger69\BurgerSpawners\Utilities\Utils;
use pocketmine\block\Block;
use pocketmine\block\MonsterSpawner as PMSpawner;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat as C;

class SpawnerBlock extends PMSpawner
{

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null): bool
    {
        if ($item->getId() !== Item::SPAWN_EGG) {
            return false;
        }

        $tile = $this->getLevel()->getTile($this);
        if (!$tile instanceof MobSpawnerTile) {
            $nbt = new CompoundTag("", [
                    new StringTag(Tile::TAG_ID, Tile::MOB_SPAWNER),
                    new IntTag(Tile::TAG_X, (int)$this->x),
                    new IntTag(Tile::TAG_Y, (int)$this->y),
                    new IntTag(Tile::TAG_Z, (int)$this->z),
                ]
            );
            /** @var MobSpawnerTile $tile */
            $tile = Tile::createTile(Tile::MOB_SPAWNER, $this->getLevel(), $nbt);
            $tile->setEntityId($item->getDamage());
        }

        return true;
    }

    /**
     * @param Item $item
     * @param Block $blockReplace
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @param Player|null $player
     * @return bool
     */
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool
    {
        parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);

        $nbt = $item->getNamedTag();
        if ($nbt->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class)) {
            $entityId = $nbt->getInt(MobSpawnerTile::ENTITY_ID);
            $tile = $this->getLevel()->getTile($this);
            if (!is_null($tile)) {
                $this->getLevel()->removeTile($tile);
            }

            if (!$tile instanceof MobSpawnerTile) {
                $nbt = new CompoundTag("", [
                    new StringTag(Tile::TAG_ID, Tile::MOB_SPAWNER),
                    new IntTag(Tile::TAG_X, (int)$this->x),
                    new IntTag(Tile::TAG_Y, (int)$this->y),
                    new IntTag(Tile::TAG_Z, (int)$this->z)
                ]);

                $tile = Tile::createTile(Tile::MOB_SPAWNER, $this->getLevel(), $nbt);
                if ($tile instanceof MobSpawnerTile) {
                    $tile->setEntityId($entityId);
                    $scale = ConfigManager::getValue("spawner-entity-scale");
                    $tile->setEntityScale($scale);
                }
            }
        }

        return true;
    }

    /**
     * @param Item $item
     * @return array
     */
    public function getDrops(Item $item): array
    {
        return [];
    }

    /**
     * @param Item $item
     * @return array
     */
    public function getSilkTouchDrops(Item $item): array
    {
        return [];
    }

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onBreak(Item $item, Player $player = null): bool
    {
        $parent = parent::onBreak($item, $player);
        if (ConfigManager::getToggle("enable-silk-touch") && !$item->hasEnchantment(Enchantment::SILK_TOUCH) && !$player->hasPermission("burgerspawners.nosilktouch")) {
            return $parent;
        }
        if (ConfigManager::getToggle("enable-silk-touch-permission") && !$player->hasPermission("burgerspawners.silktouch")) {
            return $parent;
        }
		$tile = $this->getLevel()->getTile($this->asVector3());
		if ($tile instanceof MobSpawnerTile) {
			$nbt = new CompoundTag("", [
				new IntTag(MobSpawnerTile::ENTITY_ID, $tile->getEntityId())
			]);
			$count = $tile->getCount();
			$spawner = Item::get(Item::MOB_SPAWNER, 0, $count, $nbt);
			$spawner->setCustomName(C::RESET . Utils::getEntityNameFromID((int)$tile->getEntityId()) . " Spawner");
			$this->getLevel()->dropItem($this->add(0.5, 0.5, 0.5), $spawner);
		}
        return $parent;
    }

    /**
     * @return bool
     */
    public function explode(): bool
    {
        if (ConfigManager::getToggle("enable-explosion-drop")) {
            return false;
        }
        if(mt_rand(0, 100) > 50) return false;
        $tile = $this->getLevel()->getTile($this->asVector3());
        if ($tile instanceof MobSpawnerTile) {
            $nbt = new CompoundTag("", [
                new IntTag(MobSpawnerTile::ENTITY_ID, $tile->getEntityId())
            ]);
            $count = $tile->getCount();
            $spawner = Item::get(Item::MOB_SPAWNER, 0, $count, $nbt);
            $spawner->setCustomName(C::RESET . Utils::getEntityNameFromID((int)$tile->getEntityId()) . " Spawner");
            $this->getLevel()->dropItem($this->add(0.5, 8, 0.5), $spawner);
            return true;
        }
        return false;
    }

}