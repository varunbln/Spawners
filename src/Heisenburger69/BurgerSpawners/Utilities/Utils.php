<?php
declare(strict_types=1);

namespace Heisenburger69\BurgerSpawners\Utilities;

use pocketmine\entity\EntityIds;

class Utils
{

    /**
     * @param int $entityID
     * @return string
     */
    public static function getEntityNameFromID(int $entityID): string
    {
        $names = [
            EntityIds::BAT => "Bat",
            EntityIds::BLAZE => "Blaze",
            EntityIds::CAVE_SPIDER => "Cave Spider",
            EntityIds::CHICKEN => "Chicken",
            EntityIds::COW => "Cow",
            EntityIds::CREEPER => "Creeper",
            EntityIds::DOLPHIN => "Dolphin",
            EntityIds::DONKEY => "Donkey",
            EntityIds::ELDER_GUARDIAN => "Elder Guardian",
            EntityIds::ENDERMAN => "Enderman",
            EntityIds::ENDERMITE => "Endermite",
            EntityIds::GHAST => "Ghast",
            EntityIds::GUARDIAN => "Guardian",
            EntityIds::HORSE => "Horse",
            EntityIds::HUSK => "Husk",
            EntityIds::IRON_GOLEM => "Iron Golem",
            EntityIds::LLAMA => "Llama",
            EntityIds::MAGMA_CUBE => "Magma Cube",
            EntityIds::MOOSHROOM => "Mooshroom",
            EntityIds::MULE => "Mule",
            EntityIds::OCELOT => "Ocelot",
            EntityIds::PANDA => "Panda",
            EntityIds::PARROT => "Parrot",
            EntityIds::PHANTOM => "Phantom",
            EntityIds::PIG => "Pig",
            EntityIds::POLAR_BEAR => "Polar Bear",
            EntityIds::RABBIT => "Rabbit",
            EntityIds::SHEEP => "Sheep",
            EntityIds::SHULKER => "Shulker",
            EntityIds::SILVERFISH => "Silverfish",
            EntityIds::SKELETON => "Skeleton",
            EntityIds::SKELETON_HORSE => "Skeleton Horse",
            EntityIds::SLIME => "Slime",
            EntityIds::SNOW_GOLEM => "Snow Golem",
            EntityIds::SPIDER => "Spider",
            EntityIds::SQUID => "Squid",
            EntityIds::STRAY => "Stray",
            EntityIds::VEX => "Vex",
            EntityIds::VILLAGER => "Villager",
            EntityIds::VINDICATOR => "Vindicator",
            EntityIds::WITCH => "Witch",
            EntityIds::WITHER_SKELETON => "Wither Skeleton",
            EntityIds::WOLF => "Wolf",
            EntityIds::ZOMBIE => "Zombie",
            EntityIds::ZOMBIE_HORSE => "Zombie Horse",
            EntityIds::ZOMBIE_PIGMAN => "Zombie Pigman",
            EntityIds::ZOMBIE_VILLAGER => "Zombie Villager",
            121 => "Fox",
            122 => "Bee",
            59 => "Ravager",
        ];

        return $names[$entityID] ?? "Monster";
    }

    /**
     * @return array
     */
    public static function getEntityArrayList(): array
    {
        $names = [
            "bat",
            "bee",
            "blaze",
            "cavespider",
            "chicken",
            "cow",
            "creeper",
            "donkey",
            "elderguardian",
            "enderman",
            "endermite",
            "fox",
            "ghast",
            "guardian",
            "horse",
            "husk",
            "irongolem",
            "llama",
            "magmacube",
            "mooshroom",
            "mule",
            "ocelot",
            "panda",
            "parrot",
            "pig",
            "polarbear",
            "rabbit",
            "ravager",
            "sheep",
            "shulker",
            "silverfish",
            "skeleton",
            "slime",
            "snowgolem",
            "spider",
            "squid",
            "stray",
            "vex",
            "vindicator",
            "witch",
            "witherskeleton",
            "wolf",
            "zombie",
            "pigzombie",
            "zombievillager"];
        return $names;
    }

    /**
     * @param string $entityName
     * @return int|null
     */
    public static function getEntityIDFromName(string $entityName): ?int
    {
        $names = [
            "bat" => EntityIds::BAT,
            "bee" => 122,
            "blaze" => EntityIds::BLAZE,
            "cavespider" => EntityIds::CAVE_SPIDER,
            "chicken" => EntityIds::CHICKEN,
            "cow" => EntityIds::COW,
            "creeper" => EntityIds::CREEPER,
            "dolphin" => EntityIds::DOLPHIN,
            "donkey" => EntityIds::DONKEY,
            "elderguardian" => EntityIds::ELDER_GUARDIAN,
            "enderman" => EntityIds::ENDERMAN,
            "endermite" => EntityIds::ENDERMITE,
            "fox" => 121,
            "ghast" => EntityIds::GHAST,
            "guardian" => EntityIds::GUARDIAN,
            "horse" => EntityIds::HORSE,
            "husk" => EntityIds::HUSK,
            "irongolem" => EntityIds::IRON_GOLEM,
            "llama" => EntityIds::LLAMA,
            "magmacube" => EntityIds::MAGMA_CUBE,
            "mooshroom" => EntityIds::MOOSHROOM,
            "mule" => EntityIds::MULE,
            "ocelot" => EntityIds::OCELOT,
            "panda" => EntityIDS::PANDA,
            "parrot" => EntityIds::PARROT,
            "phantom" => EntityIds::PHANTOM,
            "pig" => EntityIds::PIG,
            "polarbear" => EntityIds::POLAR_BEAR,
            "rabbit" => EntityIds::RABBIT,
            "ravager" => 59,
            "sheep" => EntityIds::SHEEP,
            "shulker" => EntityIds::SHULKER,
            "silverfish" => EntityIds::SILVERFISH,
            "skeleton" => EntityIds::SKELETON,
            "skeltonhorse" => EntityIds::SKELETON_HORSE,
            "slime" => EntityIds::SLIME,
            "snowgolem" => EntityIds::SNOW_GOLEM,
            "spider" => EntityIds::SPIDER,
            "squid" => EntityIds::SQUID,
            "stray" => EntityIds::STRAY,
            "vex" => EntityIds::VEX,
            "villager" => EntityIds::VILLAGER,
            "vindicator" => EntityIds::VINDICATOR,
            "witch" => EntityIds::WITCH,
            "witherskeleton" => EntityIds::WITHER_SKELETON,
            "wolf" => EntityIds::WOLF,
            "zombie" => EntityIds::ZOMBIE,
            "zombiehorse" => EntityIds::ZOMBIE_HORSE,
            "pigzombie" => EntityIds::ZOMBIE_PIGMAN,
            "zombievillager" => EntityIds::ZOMBIE_VILLAGER,
        ];

        return isset($names[$entityName]) ? $names[$entityName] : null;
    }

}