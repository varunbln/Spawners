<?php

namespace Heisenburger69\BurgerSpawners\Tiles;

use Heisenburger69\BurgerSpawners\Utilities\Utils;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Spawnable;

//Basic code structure from SimpleSpawners by XenialDan, ty <3

class MobSpawnerTile extends Spawnable
{

    /** @var string */
    public const LOAD_RANGE = "LoadRange";
    /** @var string */
    public const ENTITY_ID = "EntityID";
    /** @var string */
    public const SPAWN_RANGE = "SpawnRange";
    /** @var string */
    public const DELAY = "Delay";


    /** @var CompoundTag */
    private $nbt;

    /**
     * MobSpawnerTile constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt)
    {
        if ($nbt->hasTag(self::ENTITY_ID, StringTag::class)) {
            $nbt->removeTag(self::LOAD_RANGE);
            $nbt->removeTag(self::SPAWN_RANGE);
            $nbt->removeTag(self::DELAY);
        }
        if (!$nbt->hasTag(self::LOAD_RANGE, IntTag::class)) {
            $nbt->setInt(self::LOAD_RANGE, 15, true);
        }
        if (!$nbt->hasTag(self::ENTITY_ID, IntTag::class)) {
            $nbt->setInt(self::ENTITY_ID, 0, true);
        }
        if (!$nbt->hasTag(self::SPAWN_RANGE, IntTag::class)) {
            $nbt->setInt(self::SPAWN_RANGE, 4, true);
        }
        if (!$nbt->hasTag(self::DELAY, IntTag::class)) {
            $nbt->setInt(self::DELAY, 800, true);
        }
        parent::__construct($level, $nbt);
        if ($this->getEntityId() > 0) {
            $this->scheduleUpdate();
        }
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
        $nbt->setInt(self::LOAD_RANGE, $this->getNBT()->getInt(self::LOAD_RANGE), true);
        $nbt->setInt(self::ENTITY_ID, $this->getNBT()->getInt(self::ENTITY_ID), true);
        $nbt->setInt(self::DELAY, $this->getNBT()->getInt(self::DELAY), true);
        $nbt->setInt(self::SPAWN_RANGE, $this->getNBT()->getInt(self::SPAWN_RANGE), true);
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

    /**
     * @return CompoundTag
     */
    public function getNBT(): CompoundTag
    {
        return $this->nbt;
    }

    /**
     * @return bool
     */
    public function onUpdate(): bool
    {
        if($this->closed === true){
            return false;
        }
        $this->timings->startTiming();
        if($this->canUpdate()){
            if($this->getDelay() <= 0){
                $success = 0;
                for($i = 0; $i < 16; $i++){
                    $pos = $this->add(mt_rand() / mt_getrandmax() * $this->getSpawnRange(), mt_rand(-1, 1), mt_rand() / mt_getrandmax() * $this->getSpawnRange());
                    $target = $this->getLevel()->getBlock($pos);
                    if($target->getId() == Item::AIR){
                        $success++;
                        $entity = Entity::createEntity($this->getEntityId(), $this->getLevel(), Entity::createBaseNBT($target->add(0.5, 0, 0.5), null, lcg_value() * 360, 0));
                        if($entity instanceof Entity){
                            $entity->spawnToAll();
                        }
                    }
                }
                if($success > 0){
                    $this->setDelay($this->getDelay());
                }
            }else{
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
        $hasPlayer = false;
        foreach($this->getLevel()->getEntities() as $e){
            if($e instanceof Player){
                if($e->distance($this->getBlock()) <= 15){
                    $hasPlayer = true;
                }
            }
        }
        if($hasPlayer){
            return true;
        }
        return false;
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
    public function getEntityId(): int
    {
        return $this->getNBT()->getInt(self::ENTITY_ID);
    }

    /**
     * @param int $id
     */
    public function setEntityId(int $id)
    {
        $this->getNBT()->setInt(self::ENTITY_ID, $id, true);
        $this->onChanged();
        $this->scheduleUpdate();
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
    public function setLoadRange(int $range)
    {
        $this->getNBT()->setInt(self::LOAD_RANGE, true);
    }

    /**
     * @return int
     */
    public function getSpawnRange()
    {
        return $this->getNBT()->getInt(self::SPAWN_RANGE);
    }

    /**
     * @param int $value
     */
    public function setSpawnRange(int $value)
    {
        $this->getNBT()->setInt(self::SPAWN_RANGE, $value, true);
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        return $this->getNBT()->getInt(self::DELAY);
    }

    /**
     * @param int $value
     */
    public function setDelay(int $value)
    {
        $this->getNBT()->setInt(self::DELAY, $value, true);
    }

}