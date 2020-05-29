<?php

namespace Heisenburger69\BurgerSpawners\Entities;

use function mt_rand;

class Stray extends Skeleton {

    public const NETWORK_ID = self::STRAY;

    public function getName(): string{
        return "Stray";
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}