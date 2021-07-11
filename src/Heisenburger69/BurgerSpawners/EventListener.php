<?php

namespace Heisenburger69\BurgerSpawners;

use Heisenburger69\BurgerSpawners\events\SpawnerStackEvent;
use Heisenburger69\BurgerSpawners\items\SpawnEgg;
use Heisenburger69\BurgerSpawners\items\SpawnerBlock;
use Heisenburger69\BurgerSpawners\tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\utils\ConfigManager;
use Heisenburger69\BurgerSpawners\utils\Forms;
use Heisenburger69\BurgerSpawners\utils\Mobstacker;
use Heisenburger69\BurgerSpawners\utils\Utils;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemBlock;
use pocketmine\item\Pickaxe;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat as C;
use function str_replace;

/**
 * Class EventListener
 * @package Heisenburger69\BurgerSpawners
 */
class EventListener implements Listener
{
    /**
     * @var Main
     */
    private Main $plugin;

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

        if (!$entity instanceof Living || $entity instanceof Human) {
            return;
        }

        if (in_array(strtolower($entity->getSaveId()), $this->plugin->exemptedEntities)) return;

        $mobStacker = new Mobstacker($entity);
        if ($entity->getHealth() - $event->getFinalDamage() <= 0) {
            $cause = null;
            if($event instanceof EntityDamageByEntityEvent) {
                $player = $event->getDamager();
                if($player instanceof Player) {
                    $cause = $player;
                }
            }
            if ($mobStacker->removeStack($cause)) {
                if(!ConfigManager::getToggle("one-shot-mobs")) $entity->setHealth($entity->getMaxHealth());
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
        $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($entity): void {
              if(!$entity instanceof Living) return;
            if(!in_array(str_replace(" ", "", strtolower($entity->getName())), Utils::getEntityArrayList())) return;
            if (in_array(strtolower($entity->getSaveId()), $this->plugin->exemptedEntities)) return;
            if(in_array(Utils::getEntityNameFromID($entity->getId()), $this->plugin->exemptedEntities)) return;
            if($entity->getLevel() === null) return;
            if($entity->getLevel()->isClosed()) return;
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
        }), 1);
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
                    ($spawnerEvent = new SpawnerStackEvent($player, $tile, 1))->call();
                    if($spawnerEvent->isCancelled()) return;
                    $tile->setCount($tile->getCount() + 1);
                    $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                    $event->setCancelled();
                }
            }
        }
    }


    /**
     * @param PlayerInteractEvent $event
     * @priority MONITOR
     */
    public function onInteractSpawner(PlayerInteractEvent $event): void
    {
        $item = $event->getItem();
        if ($item instanceof Pickaxe) {
            return;
        }
        if($item instanceof ItemBlock && !$item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class)) {
            return;
        }

        $player = $event->getPlayer();
        $vec3 = $event->getBlock()->asVector3();
        $level = $player->getLevel();
        $tile = $level->getTile($vec3);

        if (!$tile instanceof MobSpawnerTile) {
            if($item instanceof SpawnEgg){
                $item->pop();
            }
            return;
        }

        if($event->isCancelled()) {
            $message = ConfigManager::getMessage("cannot-use-spawners-here");
            if($message === "") {
                $message = C::colorize("&4You cannot use Spawners here!");
            }
            $player->sendMessage($message);
            return;
        }

        $nbt = $item->getNamedTag();

        $disabledWorlds = ConfigManager::getArray("spawner-stacking-disabled-worlds");
        if (is_array($disabledWorlds)) {
            if (in_array($level->getFolderName(), $disabledWorlds)) {
                return;
            }
        }


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

        if (ConfigManager::getToggle("allow-spawner-stacking")) {
            Forms::sendSpawnerForm($tile, $player);
            $event->setCancelled(true);
        }
    }

    /**
     * @param EntityExplodeEvent $event
     */
    public function onExplode(EntityExplodeEvent $event): void
    {
        $blocks = $event->getBlockList();
        foreach ($blocks as $block) {
            if($block instanceof SpawnerBlock) {
                $block->explode();
            }
        }
    }

    /**
     * @param EntityDeathEvent $event
     */
    public function onDeath(EntityDeathEvent $event): void
    {
        $entity = $event->getEntity();
        if($entity instanceof Player)return;
        if (in_array(strtolower($entity->getId()), $this->plugin->exemptedEntities)) {
            $key = array_search($entity->getId(), $this->plugin->exemptedEntities);
            unset($key);
        }
    }

}
