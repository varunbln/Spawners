<?php

namespace Heisenburger69\BurgerSpawners\Tiles;

use Heisenburger69\BurgerSpawners\Utilities\ConfigManager;
use Heisenburger69\BurgerSpawners\Utilities\Forms;
use Heisenburger69\BurgerSpawners\Utilities\Utils;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Spawnable;

//Basic code structure from SimpleSpawners by XenialDan, ty <3

class MobSpawnerTile extends Spawnable
{

    /** @var string */
    public const LOAD_RANGE = "LoadRange";//Distance for player beyond which no mobs are spawned
    /** @var string */
    public const ENTITY_ID = "EntityID";//ID of the Entity
    /** @var string */
    public const SPAWN_RANGE = "SpawnRange";//Radius around the spawner in which the mob might spawn
    /** @var string */
    public const BASE_DELAY = "BaseDelay";//Delay in ticks between spawning of mobs
    /** @var string */
    public const DELAY = "Delay";//Current Delay in ticks before the next mob is spawned
    /** @var string */
    public const COUNT = "Count";//Number of spawners stacked

    /** @var CompoundTag */
    private $nbt;

    /**
     * MobSpawnerTile constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt)
    {
        $range = (int)ConfigManager::getValue("spawn-range");
        if($range === 0) { //Patch for outdated configs without "spawn-range" entry
            $range = 8;
        }
        if (!$nbt->hasTag(self::LOAD_RANGE, IntTag::class)) {
            $nbt->setInt(self::LOAD_RANGE, $range, true);
        }
        if (!$nbt->hasTag(self::ENTITY_ID, IntTag::class)) {
            $nbt->setInt(self::ENTITY_ID, 0, true);
        }
    
        if (!$nbt->hasTag(self::SPAWN_RANGE, IntTag::class)) {
            $nbt->setInt(self::SPAWN_RANGE, 4, true); //todo configure?
        }
        $base = (int)ConfigManager::getValue("base-spawn-rate");
        $base = $base * 20;
        if (!$nbt->hasTag(self::BASE_DELAY, IntTag::class)) {
            $nbt->setInt(self::BASE_DELAY, $base, true);
        }
        if (!$nbt->hasTag(self::DELAY, IntTag::class)) {
            $nbt->setInt(self::DELAY, $base, true);
        }
        if (!$nbt->hasTag(self::COUNT, IntTag::class)) {
            $nbt->setInt(self::COUNT, 1, true);
        }

        parent::__construct($level, $nbt);
        if ($this->getEntityId() > 0) {
            $this->scheduleUpdate();
        }
    }

    /**
     * @return bool
     */
    public function onUpdate(): bool
    {
        if ($this->closed === true) {
            return false;
        }
        $this->timings->startTiming();
        if ($this->canUpdate()) {
            if ($this->getDelay() <= 0) {
                $success = 0;
                for ($i = 0; $i < 16; $i++) {
                    if ($success > 0) {
                        $this->setDelay($this->getBaseDelay());
                        return true;
                    }
                    $pos = $this->add(mt_rand() / mt_getrandmax() * $this->getSpawnRange(), mt_rand(-1, 1), mt_rand() / mt_getrandmax() * $this->getSpawnRange());
                    $target = $this->getLevel()->getBlock($pos);
                    if ($target->getId() == Item::AIR) {
                        $success++;
                        $entity = Entity::createEntity($this->getEntityId(), $this->getLevel(), Entity::createBaseNBT($target->add(0.5, 0, 0.5), null, lcg_value() * 360, 0));
                        if ($entity instanceof Entity) {
                            $entity->spawnToAll();
                        }
                    }
                }
                if ($success > 0) {
                    $this->setDelay($this->getBaseDelay());
                }
            } else {
                $this->setDelay($this->getDelay() - 1);
            }
        }
        $this->timings->stopTiming();
        return true;
    }

    /**
     * @return bool
     */
    public function canUpdate(): bool
    {
        if (!$this->getLevel()->isChunkLoaded($this->getX() >> 4, $this->getZ() >> 4)) {
            return false;
        }
        if ($this->getEntityId() === 0) {
            return false;
        }
        
        if ($this->getLevel()->getNearestEntity($this, 25, Human::class) instanceof Player) {
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->getNBT()->getInt(self::DELAY);
    }

    /**
     * @param int $value
     */
    public function setDelay(int $value): void
    {
        $this->getNBT()->setInt(self::DELAY, $value, true);
    }

    /**
     * @return int
     */
    public function getBaseDelay(): int
    {
        $count = $this->getCount();
        $baseDelay = 800 / $count;
        $this->setBaseDelay($baseDelay);
        return $baseDelay;
    }

    /**
     * @param int $value
     */
    public function setBaseDelay(int $value): void
    {
        $this->getNBT()->setInt(self::BASE_DELAY, $value, true);
    }

    /**
     * @return int
     */
    public function getSpawnRange(): int
    {
        return $this->getNBT()->getInt(self::SPAWN_RANGE);
    }

    /**
     * @param int $value
     */
    public function setSpawnRange(int $value): void
    {
        $this->getNBT()->setInt(self::SPAWN_RANGE, $value, true);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->getNBT()->getInt(self::COUNT);
    }

    /**
     * @param int $value
     */
    public function setCount(int $value): void
    {
        $this->getNBT()->setInt(self::COUNT, $value, true);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return Utils::getEntityNameFromID($this->getEntityId()) . " Spawner";
    }

    /**
     * @return int
     */
    public function getLoadRange(): int
    {
        return $this->getNBT()->getInt(self::LOAD_RANGE);
    }

    /**
     * @param int $range
     */
    public function setLoadRange(int $range): void
    {
        $this->getNBT()->setInt(self::LOAD_RANGE, $range, true);
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getNBT()->getInt(self::ENTITY_ID);
    }

    /**
     * @param int $id
     */
    public function setEntityId(int $id): void
    {
        $this->getNBT()->setInt(self::ENTITY_ID, $id, true);
        $this->onChanged();
        $this->scheduleUpdate();
    }

    /**
     * @param float $scale
     */
    public function setEntityScale(float $scale): void
    {
        $this->getNBT()->setFloat("DisplayEntityScale", $scale, true);
        $this->onChanged();
        $this->scheduleUpdate();
    }

    /**
     * @return CompoundTag
     */
    public function getNBT(): CompoundTag
    {
        return $this->nbt;
    }

    /**
     * @param CompoundTag $nbt
     */
    public function addAdditionalSpawnData(CompoundTag $nbt): void
    {
        $this->baseData($nbt);
    }

    /**
     * @param CompoundTag $nbt
     */
    private function baseData(CompoundTag $nbt): void
    {
        $nbt->setInt("EntityId", $this->getNBT()->getInt(self::ENTITY_ID), true);
        $nbt->setInt(self::LOAD_RANGE, $this->getNBT()->getInt(self::LOAD_RANGE), true);
        $nbt->setInt(self::ENTITY_ID, $this->getNBT()->getInt(self::ENTITY_ID), true);
        $nbt->setInt(self::DELAY, $this->getNBT()->getInt(self::DELAY), true);
        $nbt->setInt(self::SPAWN_RANGE, $this->getNBT()->getInt(self::SPAWN_RANGE), true);
        $nbt->setInt(self::COUNT, $this->getNBT()->getInt(self::COUNT), true);
    }

    /**
     * @param CompoundTag $nbt
     */
    protected function readSaveData(CompoundTag $nbt): void
    {
        $this->nbt = $nbt;
    }

    /**
     * @param CompoundTag $nbt
     */
    protected function writeSaveData(CompoundTag $nbt): void
    {
        $this->baseData($nbt);
    }

    public function sendAddSpawnersForm(Player $player): void
    {
        Forms::sendAddSpawnerForm($player, $this);
    }

    public function sendRemoveSpawnersForm(Player $player): void
    {
        Forms::sendRemoveSpawnersForm($player, $this);
    }
}
