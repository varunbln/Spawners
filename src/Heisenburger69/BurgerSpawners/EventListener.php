<?php

namespace Heisenburger69\BurgerSpawners;

use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\Utilities\ConfigManager;
use Heisenburger69\BurgerSpawners\Utilities\Forms;
use Heisenburger69\BurgerSpawners\Utilities\Mobstacker;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Pickaxe;
use pocketmine\nbt\tag\IntTag;

/**
 * Class EventListener
 * @package Heisenburger69\BurgerSpawners
 */
class EventListener implements Listener
{
    /**
     * @var Main
     */
    private $plugin;

    /**
     * EventListener constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof Living or $entity instanceof Human) {
            return;
        }

        $mobStacker = new Mobstacker($entity);
        if ($entity->getHealth() - $event->getFinalDamage() <= 0) {
            if ($mobStacker->removeStack()) {
                $entity->setHealth($entity->getMaxHealth());
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param EntitySpawnEvent $event
     */
    public function onSpawn(EntitySpawnEvent $event): void
    {
        $entity = $event->getEntity();

        if (in_array($entity->getId(), $this->plugin->exemptedEntities)) return;

        $disabledWorlds = ConfigManager::getArray("mob-stacking-disabled-worlds");
        if (is_array($disabledWorlds)) {
            if (in_array($entity->getLevel()->getFolderName(), $disabledWorlds)) {
                return;
            }
        }

        if (ConfigManager::getToggle("allow-mob-stacking")) {
            if ($entity instanceof Human or !$entity instanceof Living) return;
            $mobStacker = new Mobstacker($entity);
            $mobStacker->stack();
        }
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlaceSpawner(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();
        $tiles = $block->getLevel()->getChunkTiles($block->getX() >> 4, $block->getZ() >> 4);

        $disabledWorlds = ConfigManager::getArray("spawner-stacking-disabled-worlds");
        if (is_array($disabledWorlds)) {
            if (in_array($player->getLevel()->getFolderName(), $disabledWorlds)) {
                return;
            }
        }

        foreach ($tiles as $tile) {
            if (!$tile instanceof MobSpawnerTile) {
                return;
            }
            if (ConfigManager::getToggle("allow-spawner-stacking")) {
                if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $tile->getEntityId()) {
                    $tile->setCount($tile->getCount() + 1);
                    $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                    $event->setCancelled();
                }
            }
        }
    }


    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteractSpawner(PlayerInteractEvent $event): void
    {
        $item = $event->getItem();
        if ($item instanceof Pickaxe) {
            return;
        }
        $nbt = $item->getNamedTag();
        $player = $event->getPlayer();
        $level = $player->getLevel();

        $disabledWorlds = ConfigManager::getArray("spawner-stacking-disabled-worlds");
        if (is_array($disabledWorlds)) {
            if (in_array($level->getFolderName(), $disabledWorlds)) {
                return;
            }
        }

        $vec3 = $event->getBlock()->asVector3();
        $tile = $level->getTile($vec3);
        if ($nbt->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class)) {
            if (!$tile instanceof MobSpawnerTile) {
                return;
            }
            if (ConfigManager::getToggle("allow-spawner-stacking")) {
                Forms::sendSpawnerForm($tile, $player);
                $event->setCancelled(true);
            }
            return;
        }
        if (!$tile instanceof MobSpawnerTile) {
            return;
        }
        if (ConfigManager::getToggle("allow-spawner-stacking")) {
            Forms::sendSpawnerForm($tile, $player);
            $event->setCancelled(true);
        }
    }

    /**
     * @param EntityDeathEvent $event
     */
    public function onDeath(EntityDeathEvent $event): void
    {
        $entity = $event->getEntity();
        if (in_array($entity->getId(), $this->plugin->exemptedEntities)) {
            $key = array_search($entity->getId(), $this->plugin->exemptedEntities);
            unset($key);
        }
    }

}
