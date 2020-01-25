<?php

namespace Heisenburger69\BurgerSpawners\Commands;

use Heisenburger69\BurgerSpawners\Main;
use Heisenburger69\BurgerSpawners\Utilities\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class SpawnerCommand extends PluginCommand
{

    /** @var Main */
    private $plugin;
    /**
     * SpawnerCommand constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin){
        parent::__construct("spawner", $plugin);
        $this->setUsage("/spawner <string:spawner> [int:count] [string:player]");
        $this->setAliases(["burgerspawners"]);
        $this->setDescription("Burger Spawners Base Command");
        $this->setPermission("burgerspawners.command.spawner");
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string        $commandLabel
     * @param array         $args
     * @return bool|mixed
     * @throws \ReflectionException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if((!isset($args[0])) || ($args[0] === "help")){
            $sender->sendMessage(Main::PREFIX . "§cUsage: /spawner <string:spawner> [int:count][string:player]");
            return false;
        }

        $count = isset($args[1]) ? (int) $args[1] : 1;
        $player = isset($args[2]) ? $this->plugin->getServer()->getPlayer($args[2]) : $sender;
        $entities = array_change_key_case($this->plugin->getRegisteredEntities(), CASE_LOWER);
        $entityName = strtolower($args[0]);
        $delay = isset($args[3]) ? (int) $args[3] : 800;

        if(!array_key_exists($entityName, $entities)){
            $sender->sendMessage(Main::PREFIX . "§cSpawner for the entity with the name §2" . $args[0] . " §ccould not be found. Make sure that the entity is registered.");

            return false;
        }

        $entityID = Utils::getEntityIDFromName($entityName);

        $nbt = new CompoundTag("", [
            new IntTag("EntityID", (int) $entityID),
            new IntTag("MaxSpawnDelay", (int) $delay),
        ]);

        $spawner = Item::get(Item::MOB_SPAWNER, 0, $count, $nbt);
        $spawner->setCustomName(C::RESET.$spawnerName = Utils::getEntityNameFromID((int) $entityID) . " Spawner");

        if(($player instanceof Player) && ($player->isOnline())){
            $sender->sendMessage(Main::PREFIX . "§aPlayer §d" . $player->getName() . " §ahas been given a §e" . $spawnerName);

            $player->sendMessage(Main::PREFIX . "§aYou have been given a §d" . $spawnerName);
            $player->getInventory()->addItem($spawner);

            return true;
        }else{
            $sender->sendMessage(Main::PREFIX . "§cPlayer could not be found.");
        }
        return false;
    }
}