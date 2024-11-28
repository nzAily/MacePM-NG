<?php

namespace XeonCh\Mace\item;

use pocketmine\entity\Entity;
use pocketmine\item\Sword;

class Mace extends Sword {

    public function getDamage(): int
    {
        return 6;
    }

    public function onAttackEntity(Entity $victim, array &$returnedItems): bool
    {
        return $this->applyDamage(1);
    }
}