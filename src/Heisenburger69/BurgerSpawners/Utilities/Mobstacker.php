<?php

namespace Heisenburger69\BurgerSpawners\Utilities;

use Heisenburger69\BurgerSpawners\Main;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

/*
Modified version of MobStacker by UnknownOre
Modified to fix:
    1. Mobs instantly dying and taking no kb when in a stack
    2. Mobs not dropping experience orbs
TODO: Submit a PR to the plugin.
*/

/**
 * Class Mobstacker
 * @package Heisenburger69\BurgerSpawners\Utilities
 */
class Mobstacker
{

    /** @var string */
    CONST STACK = 'stack';
    /* @var Living */
    private $entity;

    /**
     * Mobstacker constructor.
     * @param Living $entity
     */
    public function __construct(Living $entity)
    {
        $this->entity = $entity;
    }

    public function stack(): void
    {
        if ($this->isStacked()) {
            $this->updateNameTag();
            return;
        }
        if (($mob = $this->findNearStack()) == null) {
            $nbt = new IntTag(self::STACK, 1);
            $this->entity->namedtag->setTag($nbt);
            $mobstack = $this;
        } else {
            $this->entity->flagForDespawn();
            $mobstack = new Mobstacker($mob);
            $count = $mob->namedtag->getInt(self::STACK);
            $mob->namedtag->setInt(self::STACK, ++$count);
        }
        $mobstack->updateNameTag();
    }

    /**
     * @return bool
     */
    public function isStacked(): bool
    {
        return $this->entity->namedtag->hasTag(self::STACK);
    }

    public function updateNameTag(): void
    {
        $nbt = $this->entity->namedtag;
        $this->entity->setNameTagAlwaysVisible(True);
        $this->entity->setNameTag(C::BOLD . C::AQUA . $nbt->getInt(self::STACK) . 'x ' . C::BOLD . C::GOLD . $this->entity->getName());
    }

    /**
     * @param int $range
     * @return Living|null
     */
    public function findNearStack(int $range = 16): ?Living
    {
        $entity = $this->entity;
        if ($entity->isFlaggedForDespawn() or $entity->isClosed()) return null;
        $boundingBox = $entity->getBoundingBox()->expandedCopy($range, $range, $range);
        foreach ($entity->getLevel()->getNearbyEntities($boundingBox) as $e) {
            if (!$e instanceof Player and $e instanceof Living) {
                if ($e->distance($entity) <= $range and $e->getName() === $entity->getName() && !in_array($entity->getId(), Main::$instance->exemptedEntities)) {
                    $ae = new Mobstacker($e);
                    if ($ae->isStacked() and !$this->isStacked()) return $e;
                }
            }
        }
        return null;
    }

    /**
     * @param Player|null $player
     * @return bool
     */
    public function removeStack(Player $player = null): bool
    {
        $entity = $this->entity;
        $nbt = $entity->namedtag;
        if (!$this->isStacked() or ($c = $this->getStackAmount()) <= 1) {
            return false;
        }
        $nbt->setInt(self::STACK, --$c);
        $event = new EntityDeathEvent($entity, $drops = $this->getDrops($entity));
        $event->call();
        $this->updateNameTag();

        if(ConfigManager::getToggle("mobs-drop-items")) {
            foreach ($drops as $drop) {
                if($player instanceof Player && ConfigManager::getToggle("auto-inv")) {
                    $player->getInventory()->addItem($drop);
                } else {
                    $entity->getLevel()->dropItem($entity->getPosition(), $drop);
                }
            }
        }
        if(ConfigManager::getToggle("mobs-drop-exp")) {
            $exp = $entity->getXpDropAmount();
            if ($exp > 0) {
                if($player instanceof Player && ConfigManager::getToggle("auto-xp")) {
                    $player->addXp($exp);
                } else {
                    $entity->getLevel()->dropExperience($entity->asVector3(), $exp);
                }
            }
        }
        return true;
    }

    /**
     * @return int
     */
    public function getStackAmount(): int
    {
        return $this->entity->namedtag->getInt(self::STACK);
    }
    
    /**
     * @param Living $entity
     * @return bool
     */
    public function hasCustomDrops(Living $entity): bool
    {
        return isset(ConfigManager::getArray("custom-mob-drops")[strtolower($entity->getName())]);
    }

    /**
     * @param Living $entity
     * @return array
     */
    public function getDrops(Living $entity): array
    {
        if ($this->hasCustomDrops($entity)) {
            $dropData = ConfigManager::getArray("custom-mob-drops")[strtolower($entity->getName())];
            $drops = [];
            foreach ($dropData as $dropString) {
                $drops[] = Item::fromString($dropString);
            }
            return $drops;
        } else {
            return $entity->getDrops();
        }
    }
    
}
