<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Monster;
use function mt_rand;

class Witch extends Monster {

    public const NETWORK_ID = self::WITCH;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string{
        return "Witch";
    }

    public function initEntity(): void{
        $this->setMaxHealth(26);
        parent::initEntity();
    }

    public function getDrops(): array{
        return [];
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}