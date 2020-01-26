<?php

namespace Heisenburger69\BurgerSpawners;

use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\Utilities\Forms;
use Heisenburger69\BurgerSpawners\Utilities\Mobstacker;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageEvent;
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
        if ($entity instanceof Human or !$entity instanceof Living) return;
        $mobStacker = new Mobstacker($entity);
        $mobStacker->stack();
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onPlaceSpawner(PlayerInteractEvent $event): void
    {
        $item = $event->getItem();
        if($item instanceof Pickaxe) {
            return;
        }
        $nbt = $item->getNamedTag();
        $player = $event->getPlayer();
        $vec3 = $event->getBlock()->asVector3();
        $level = $player->getLevel();
        $tile = $level->getTile($vec3);
        if ($nbt->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class)) {
            if (!$tile instanceof MobSpawnerTile) {
                return;
            }
            Forms::sendSpawnerForm($tile, $player);
            $event->setCancelled(true);
            return;
        }
        if (!$tile instanceof MobSpawnerTile) {
            return;
        }
        Forms::sendSpawnerForm($tile, $player);
        $event->setCancelled(true);
    }


}