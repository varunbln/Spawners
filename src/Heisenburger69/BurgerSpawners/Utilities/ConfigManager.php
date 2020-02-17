<?php

namespace Heisenburger69\BurgerSpawners\Utilities;

use Heisenburger69\BurgerSpawners\Main;
use pocketmine\utils\TextFormat as C;

class ConfigManager
{
    /**
     * @param string $messageTag
     * @return string
     */
    public static function getMessage(string $messageTag): string
    {
        $message = (string) Main::getInstance()->getConfig()->get($messageTag);
        $message = C::colorize($message);
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

    /**
     * @param string $arrayTag
     * @return array|bool
     */
    public static function getArray(string $arrayTag)
    {
        $array = Main::getInstance()->getConfig()->get($arrayTag);
        return $array;
    }
}
