<?php

declare(strict_types=1);

namespace Heisenburger69\BurgerSpawners;

use Heisenburger69\BurgerSpawners\Commands\SpawnerCommand;
use Heisenburger69\BurgerSpawners\Entities\EntityManager;
use Heisenburger69\BurgerSpawners\Items\SpawnEgg;
use Heisenburger69\BurgerSpawners\Items\SpawnerBlock;
use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat as C;
use ReflectionException;
use ReflectionProperty;
use JackMD\UpdateNotifier\UpdateNotifier;


class Main extends PluginBase
{

    /** @var string */
    public const PREFIX = C::BOLD . C::AQUA . "Burger" . C::LIGHT_PURPLE . "Spawners" . "> " . C::RESET;

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("BurgerSpawners", new SpawnerCommand($this));
        /** @noinspection PhpUnhandledExceptionInspection */
        Tile::registerTile(MobSpawnerTile::class, [Tile::MOB_SPAWNER, "minecraft:mob_spawner"]);
        BlockFactory::registerBlock(new SpawnerBlock(), true);
        ItemFactory::registerItem(new SpawnEgg(), true);
        Item::initCreativeItems();
        EntityManager::init();//Only registering Iron Golems for now
        UpdateNotifier::checkUpdate($this, $this->getDescription()->getName(), $this->getDescription()->getVersion());
    }

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public function getRegisteredEntities(): ?array
    {
        $reflectionProperty = new ReflectionProperty(Entity::class, 'knownEntities');
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue();
    }

}
