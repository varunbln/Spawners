<?php

namespace Heisenburger69\BurgerSpawners;

use Heisenburger69\BurgerSpawners\Items\SpawnerBlock;
use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\Utilities\Forms;
use Heisenburger69\BurgerSpawners\Utilities\Mobstacker;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;

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
        if (!$entity instanceof Living or $entity instanceof Player) {
            return;
        }
        $mobStacker = new Mobstacker($entity);
        if ($entity->getHealth() - $event->getFinalDamage() <= 0) {
            if ($mobStacker->removeStack()) {
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
        if ($entity instanceof Player or !$entity instanceof Living) return;
        $mobStacker = new Mobstacker($entity);
        $mobStacker->stack();
    }


    /**
     * @param PlayerInteractEvent $event
     */
    public function onPlaceSpawner(PlayerInteractEvent $event): void
    {
        $item = $event->getItem();
        $nbt = $item->getNamedTag();
        if ($nbt->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class)) {
            $player = $event->getPlayer();
            $vec3 = $event->getBlock()->asVector3();
            $level = $player->getLevel();
            $tile = $level->getTile($vec3);
            if (!$tile instanceof MobSpawnerTile) {
                return;
            }
            Forms::sendSpawnerForm($tile, $player);
            $event->setCancelled(true);
            return;
        }
    }


}