<?php


namespace Heisenburger69\BurgerSpawners\Entities;


use pocketmine\entity\Animal;

class Panda extends Animal
{

    public const NETWORK_ID = self::PANDA;

    public $width = 1.2;
    public $height = 1.2;

    public function getName(): string
    {
        return "Panda";
    }
}