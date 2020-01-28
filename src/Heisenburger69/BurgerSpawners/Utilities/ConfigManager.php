<?php

namespace Heisenburger69\BurgerSpawners\Utilities;

use Heisenburger69\BurgerSpawners\Main;

class ConfigManager
{
    /**
     * @param string $messageTag
     * @return string
     */
    public static function getMessage(string $messageTag): string
    {
        $message = (string) Main::getInstance()->getConfig()->get($messageTag);
        return $message;
    }

    /**
     * @param string $toggleTag
     * @return bool
     */
    public static function getToggle(string $toggleTag): bool
    {
        $toggle = (bool) Main::getInstance()->getConfig()->get($toggleTag);
        return $toggle;
    }

    /**
     * @param string $valueTag
     * @return float
     */
    public static function getValue(string $valueTag): float
    {
        $float = (float) Main::getInstance()->getConfig()->get($valueTag);
        return $float;
    }
}
