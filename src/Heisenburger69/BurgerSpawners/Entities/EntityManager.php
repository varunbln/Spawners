<?php

declare(strict_types=1);

namespace Heisenburger69\BurgerSpawners\Entities;

use pocketmine\entity\Entity;

class EntityManager extends Entity //Teaspoon <3
{
    public static function init(): void
    {
        self::registerEntity(Bat::class, true, ['Bat', 'minecraft:bat']);
        self::registerEntity(Bee::class, true, ['Bee', 'minecraft:bee']);
        self::registerEntity(Blaze::class, true, ['Blaze', 'minecraft:blaze']);
        self::registerEntity(CaveSpider::class, true, ['CaveSpider', 'minecraft:cavespider']);
        self::registerEntity(Chicken::class, true, ['Chicken', 'minecraft:chicken']);
        self::registerEntity(Cow::class, true, ['Cow', 'minecraft:cow']);
        self::registerEntity(Creeper::class, true, ['Creeper', 'minecraft:creeper']);
        self::registerEntity(Donkey::class, true, ['Donkey', 'minecraft:donkey']);
        self::registerEntity(ElderGuardian::class, true, ['ElderGuardian', 'minecraft:elderguardian']);
        self::registerEntity(Enderman::class, true, ['Enderman', 'minecraft:enderman']);
        self::registerEntity(Endermite::class, true, ['Endermite', 'minecraft:endermite']);
        self::registerEntity(Evoker::class, true, ['Evoker', 'minecraft:evoker']);
        self::registerEntity(Fox::class, true, ['Fox', 'minecraft:fox']);
        self::registerEntity(Ghast::class, true, ['Ghast', 'minecraft:ghast']);
        self::registerEntity(Guardian::class, true, ['Guardian', 'minecraft:guardian']);
        self::registerEntity(Horse::class, true, ['Horse', 'minecraft:horse']);
        self::registerEntity(Husk::class, true, ['Husk', 'minecraft:husk']);
        self::registerEntity(IronGolem::class, true, ['IronGolem', 'minecraft:irongolem']);
        self::registerEntity(Llama::class, true, ['Llama', 'minecraft:llama']);
        self::registerEntity(MagmaCube::class, true, ['MagmaCube', 'minecraft:magmacube']);
        self::registerEntity(Mooshroom::class, true, ['Mooshroom', 'minecraft:mooshroom']);
        self::registerEntity(Mule::class, true, ['Mule', 'minecraft:mule']);
        self::registerEntity(Ocelot::class, true, ['Ocelot', 'minecraft:ocelot']);
        self::registerEntity(Panda::class, true, ['Panda', 'minecraft:panda']);
        self::registerEntity(Parrot::class, true, ['Parrot', 'minecraft:parrot']);
        self::registerEntity(Pig::class, true, ['Pig', 'minecraft:pig']);
        self::registerEntity(PigZombie::class, true, ['PigZombie', 'minecraft:pigzombie']);
        self::registerEntity(PolarBear::class, true, ['PolarBear', 'minecraft:polarbear']);
        self::registerEntity(Rabbit::class, true, ['Rabbit', 'minecraft:rabbit']);
        self::registerEntity(Sheep::class, true, ['Sheep', 'minecraft:sheep']);
        self::registerEntity(Shulker::class, true, ['Shulker', 'minecraft:shulker']);
        self::registerEntity(Silverfish::class, true, ['Silverfish', 'minecraft:silverfish']);
        self::registerEntity(Skeleton::class, true, ['Skeleton', 'minecraft:skeleton']);
        self::registerEntity(Slime::class, true, ['Slime', 'minecraft:slime']);
        self::registerEntity(SnowGolem::class, true, ['SnowGolem', 'minecraft:snowgolem']);
        self::registerEntity(Spider::class, true, ['Spider', 'minecraft:spider']);
        self::registerEntity(Stray::class, true, ['Stray', 'minecraft:stray']);
        self::registerEntity(Vex::class, true, ['Vex', 'minecraft:vex']);
        self::registerEntity(Vindicator::class, true, ['Vindicator', 'minecraft:vindicator']);
        self::registerEntity(Witch::class, true, ['Witch', 'minecraft:witch']);
        self::registerEntity(WitherSkeleton::class, true, ['WitherSkeleton', 'minecraft:witherskeleton']);
        self::registerEntity(Wolf::class, true, ['Wolf', 'minecraft:wolf']);
        self::registerEntity(ZombieVillager::class, true, ['ZombieVillager', 'minecraft:zombievillager']);
    }
}