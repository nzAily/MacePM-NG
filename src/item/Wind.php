<?php

namespace XeonCh\Mace\item;

use pocketmine\color\Color;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemUseResult;
use pocketmine\item\ProjectileItem;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\particle\DustParticle;
use XeonCh\Mace\entity\WindCharge;

class Wind extends ProjectileItem
{

    public function getMaxStackSize(): int
    {
        return 64;
    }

    protected function createEntity(Location $location, Player $thrower): Throwable
    {
        return new WindCharge($location, $thrower);
    }

    public function getThrowForce(): float
    {
        return 1.5;
    }

    public function getCooldownTicks(): int
    {
        return 10;
    }

    public function getCooldownTag(): ?string
    {
        return "wind_charge";
    }
}
