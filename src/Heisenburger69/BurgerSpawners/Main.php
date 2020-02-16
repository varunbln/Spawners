<?php

declare(strict_types=1);

namespace Heisenburger69\BurgerSpawners;

use Heisenburger69\BurgerSpawners\Commands\SpawnerCommand;
use Heisenburger69\BurgerSpawners\Entities\EntityManager;
use Heisenburger69\BurgerSpawners\Items\SpawnEgg;
use Heisenburger69\BurgerSpawners\Items\SpawnerBlock;
use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\Utilities\ConfigManager;
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
use JackMD\ConfigUpdater\ConfigUpdater;

class Main extends PluginBase
{

    /** @var int  */
    private const CFGVERSION = 1;
    /** @var string */
    public const PREFIX = C::BOLD . C::AQUA . "Burger" . C::LIGHT_PURPLE . "Spawners" . "> " . C::RESET;

    /**
     * @var Main
     */
    public static $instance;

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("BurgerSpawners", new SpawnerCommand($this));

        /** @noinspection PhpUnhandledExceptionInspection */
        Tile::registerTile(MobSpawnerTile::class, [Tile::MOB_SPAWNER, "minecraft:mob_spawner"]);
        BlockFactory::registerBlock(new SpawnerBlock(), true);
        ItemFactory::registerItem(new SpawnEgg(), true);
        Item::initCreativeItems();

        if(ConfigManager::getToggle("register-mobs")) {
            EntityManager::init();
        }
        ConfigUpdater::checkUpdate($this, $this->getConfig(), "config-ver", self::CFGVERSION);

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

    public static function getInstance(): Main
    {
        return self::$instance;
    }

}
