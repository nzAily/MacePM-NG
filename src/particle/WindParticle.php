<?php

declare(strict_types=1);

namespace XeonCh\Mace\particle;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\types\ParticleIds;
use pocketmine\world\particle\Particle;

class WindParticle extends Particle implements ParticleIds {

    public function encode(Vector3 $pos) : array {
        $protocolId = ProtocolInfo::CURRENT_PROTOCOL;
        return [LevelEventPacket::standardParticle(ParticleIds::WIND_EXPLOSION, 0, $pos, $protocolId)];
    }
}
