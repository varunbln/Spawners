<?php

namespace Heisenburger69\BurgerSpawners\Utilities;

use Heisenburger69\BurgerSpawners\Main;
use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\libs\jojoe77777\FormAPI\CustomForm;
use Heisenburger69\BurgerSpawners\libs\jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use Heisenburger69\BurgerSpawners\Utilities\Utils;
class Forms
{

    /**
     * @var array
     */
    public static $usingSpawner = [];

    /**
     * @param MobSpawnerTile $spawner
     * @param Player $player
     */
    public static function sendSpawnerForm(MobSpawnerTile $spawner, Player $player): void
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $spawner = Forms::$usingSpawner[$player->getName()];
                    if ($spawner instanceof MobSpawnerTile) {
                        $spawner->sendAddSpawnersForm($player);
                    }
                    break;
                case 1:
                    
                    
                    $spawner = Forms::$usingSpawner[$player->getName()];
                    $count = $spawner->getCount();
                    if ($spawner instanceof MobSpawnerTile && $count > 1) {
                        
                        $spawner->sendRemoveSpawnersForm($player);
                        return;
                    }
                    $player->sendMessage(Main::PREFIX . "§cThis is the last spawner.Use a Pickaxe to Remove this");
                    break;
                    
                case 2:
                    break;
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();

        $form->setTitle(C::BOLD . C::DARK_BLUE . $spawnerName);
        $form->setContent(C::BOLD . C::AQUA . "Count: " . C::RESET . $count);
        $form->addButton(C::BOLD . C::GOLD . "Add Spawners");
        $form->addButton(C::BOLD . C::GOLD . "Remove Spawners");
        $form->addButton(C::BOLD . C::RED . "Close");
        $player->sendForm($form);
    }

    public static function sendAddSpawnerForm(Player $player, MobSpawnerTile $spawner): void
    {
        $form = new CustomForm(function (Player $player, array $response = null) {
            if ($response === null) {
                return;
            }
            if (isset($response[1])) {
                $spawner = Forms::$usingSpawner[$player->getName()];
                if ($spawner instanceof MobSpawnerTile) {

                    $entityId = $spawner->getEntityId();
                    $count = (int)$response[1];

                    $item = $player->getInventory()->getItemInHand();
                    if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
                        $stackCount = $item->getCount();
                        $max = $stackCount;
                    } else {
                        $message = ConfigManager::getMessage("no-available-spawners");
                        $player->sendMessage(Main::PREFIX . $message);
                        return;
                    }

                    if ($count > $max) {
                        $count = $max;
                        $message = ConfigManager::getMessage("all-spawners-stacked");
                        $player->sendMessage(Main::PREFIX . $message);
                    }

                    $item = $player->getInventory()->getItemInHand();
                    if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
                        $stackCount = $item->getCount();
                        $leftover = $stackCount - $count;
                        if($leftover > 0) {
                            $item->setCount($leftover);
                            $player->getInventory()->setItemInHand($item);
                        } else {
                            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
                        }
                    }

                    $spawner->setCount($spawner->getCount() + $count);
                }
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();
        $entityId = $spawner->getEntityId();

        $max = 1;

        $item = $player->getInventory()->getItemInHand();
        if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
            $stackCount = $item->getCount();
            $max = $stackCount;
        }

        $form->setTitle(C::BOLD . C::DARK_BLUE . $spawnerName);
        $form->addLabel(C::BOLD . C::AQUA . "Count: " . C::RESET . $count);
        $form->addSlider(C::BOLD . C::GOLD . "Number of spawners to add" . C::YELLOW, 1, $max, 1);
		$player->sendForm($form);
	}
	
	public static function getTheSpawner( $entityID, int $amount): Item
    {
        

        $nbt = new CompoundTag("", [
            new IntTag("EntityID", (int)$entityID)
        ]);

        $spawner = Item::get(Item::MOB_SPAWNER, 0, $amount, $nbt);
        $spawnerName = Utils::getEntityNameFromID((int)$entityID) . " Spawner";
        $spawner->setCustomName(C::RESET . $spawnerName);

        return $spawner;
    }
	
	public static function sendRemoveSpawnerForm(Player $player, MobSpawnerTile $spawner): void
    {
        $form = new CustomForm(function (Player $player, array $response = null) {
            if ($response === null) {
                return;
            }
            if (isset($response[1])) {
                $spawner = Forms::$usingSpawner[$player->getName()];
                if ($spawner instanceof MobSpawnerTile) {
                    $entityId = $spawner->getEntityId();
                    $entityName = Utils::getEntityNameFromID($entityId);
                    $count = (int)$response[1];
                    
                    
                       
                       
                    $player->getInventory()->addItem(Forms::getTheSpawner($entityId , $count));
                    
                    $spawner->setCount($spawner->getCount() - $count);
                }
        
            }
            
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;
        $count = $spawner->getCount();
        $spawnerName = $spawner->getName();
        $form->addLabel(C::BOLD . C::AQUA . "Count: " . C::RESET . $count);
        $form->addSlider(C::BOLD . C::GOLD . "Number of spawners to remove" . C::YELLOW, 1, $count - 1, 1);
        $form->setTitle(C::BOLD . C::DARK_BLUE . $spawnerName);
        
        $player->sendForm($form);
    } 
    
}