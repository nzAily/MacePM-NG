<?php

declare(strict_types=1);

namespace XeonCh\Mace;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\Listener;
use pocketmine\math\AxisAlignedBB;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\player\Player;
use pocketmine\world\sound\FizzSound;
use pocketmine\world\sound\PopSound;
use XeonCh\Mace\item\Mace;

class EventListener implements Listener
{

    private $playerPreviousY = [];
    private $playerFallDistance = [];

    public function onPlayerMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();

        $currentY = $event->getTo()->getY();
        $previousY = $event->getFrom()->getY();

        if ($currentY < $previousY) {
            if (!isset($this->playerFallDistance[$player->getName()])) {
                $this->playerFallDistance[$player->getName()] = 0;
            }
            $fallDistance = $this->playerFallDistance[$player->getName()] + ($previousY - $currentY);
            $this->playerFallDistance[$player->getName()] = $fallDistance;
        }
        if ($player->isOnGround()) {
            if (isset($this->playerFallDistance[$player->getName()])) {
                $fallDistance = $this->playerFallDistance[$player->getName()];
                if ($fallDistance > 1) {
                }
                unset($this->playerFallDistance[$player->getName()]);
            }
        }
    }

    public function MaceLogic(EntityDamageByEntityEvent $event)
    {
        $damager = $event->getDamager();

        if ($damager instanceof Player) {
            $player = $damager;
            $item = $player->getInventory()->getItemInHand();

            if ($item instanceof Mace) {
                if (isset($this->playerFallDistance[$player->getName()])) {
                    $fallDistance = $this->playerFallDistance[$player->getName()];
                    $damage = 0;
                    if ($fallDistance > 2) {
                        $damage = 5 * ($fallDistance - 1); 
                    }
                    $newDamage = $event->getBaseDamage() + $damage;
                    $event->setBaseDamage($newDamage);
                    $x = $player->getPosition()->getX();
                    $y = $player->getPosition()->getY();
                    $z = $player->getPosition()->getZ();

                    $nearbyP = $player->getWorld()->getNearbyEntities(new AxisAlignedBB($x - 20, $y - 20, $z - 20, $x + 20, $y + 20, $z + 20));
                    foreach ($nearbyP as $near) {
                        if ($near instanceof Player) {
                            $near->getNetworkSession()->sendDataPacket(PlaySoundPacket::create("mace.heavy_smash_ground", $x, $y, $z, 1.0, 1.0));
                            $near->getNetworkSession()->sendDataPacket(SpawnParticleEffectPacket::create(DimensionIds::OVERWORLD, -1, $event->getEntity()->getPosition()->add(0,1,0), "minecraft:smash_ground_particle_center", null));
                            $near->getNetworkSession()->sendDataPacket(SpawnParticleEffectPacket::create(DimensionIds::OVERWORLD, -1, $event->getEntity()->getPosition()->add(0, 1, 0), "minecraft:smash_ground_particle", null));
                        }
                    }
                    unset($this->playerFallDistance[$player->getName()]);
                }
            }
        }
    }
}
