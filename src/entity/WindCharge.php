<?php

namespace XeonCh\Mace\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\projectile\Throwable;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\particle\SnowballPoofParticle;
use XeonCh\Mace\particle\WindParticle;

class WindCharge extends Throwable
{
    public static function getNetworkTypeId(): string
    {
        return EntityIds::WIND_CHARGE_PROJECTILE;
    }

    protected function onHit(ProjectileHitEvent $event): void
    {
        $world = $this->getWorld();
        $world->addParticle($this->location, new WindParticle());
        $radius = 1.5;
        $boundingBox = new AxisAlignedBB(
            $this->location->x - $radius,
            $this->location->y - $radius,
            $this->location->z - $radius,
            $this->location->x + $radius,
            $this->location->y + $radius,
            $this->location->z + $radius
        );
        $nearbyEntities = $world->getNearbyEntities($boundingBox);
        foreach ($nearbyEntities as $entity) {
            if ($entity instanceof Living) {
                if ($entity->getId() === $this->getOwningEntity()?->getId()) {
                    $this->applyKnockback($entity, 2.5);
                } else {
                    $entity->attack(new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_PROJECTILE, 1));
                    $this->applyKnockback($entity, 2.5);
                }
            }
        }
        $x = $this->getPosition()->getX();
        $y = $this->getPosition()->getY();
        $z = $this->getPosition()->getZ();

        $nearbyP = $this->getOwningEntity()?->getWorld()->getNearbyEntities(new AxisAlignedBB($x - 20, $y - 20, $z - 20, $x + 20, $y + 20, $z + 20));
        foreach ($nearbyP as $near) {
            if ($near instanceof Player) {
                $near->getNetworkSession()->sendDataPacket(PlaySoundPacket::create("wind_charge.burst", $x, $y, $z, 1.0, 1.0));
            }
        }
    }

    private function applyKnockback(Living $entity, float $distance): void
    {
        $direction = $entity->getPosition()->subtractVector($this->location);
        $direction->x = 0;
        $direction->z = 0;
        $knockbackVector = $direction->normalize()->multiply($distance)->addVector(new Vector3(0, 1, 0));

        $entity->setMotion($knockbackVector);
    }
}
