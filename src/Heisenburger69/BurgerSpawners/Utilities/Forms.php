<?php

namespace Heisenburger69\BurgerSpawners\Utilities;

use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class Forms
{

    public static $usingSpawner = (array) [];

    /**
     * @param MobSpawnerTile $spawner
     * @param Player $player
     */
    public static function sendSpawnerForm(MobSpawnerTile $spawner, Player $player): void
    {
        $form = new SimpleForm(function (Player $player, $data = null) {;
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $spawner = Forms::$usingSpawner[$player->getName()];
                    if($spawner instanceof MobSpawnerTile) {
                        $spawner->setCount($spawner->getCount() + 1);
                        unset(Forms::$usingSpawner[$player->getName()]);
                    }
                    break;
                case 1:
                    break;
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();

        $form->setTitle(C::BOLD . C::GREEN . $spawnerName);
        $form->setContent(C::BOLD . C::AQUA . "Count: " . C::RESET . $count);
        $form->addButton(C::BOLD . C::GOLD . "Add +1 Spawner");
        $form->addButton(C::BOLD . C::RED . "Close");
        $form->sendToPlayer($player);
    }
}