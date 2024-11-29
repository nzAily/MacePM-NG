<?php

namespace XeonCh\Mace\particle;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\world\particle\Particle;

class WindParticle implements Particle
{

    public const WIND_EXPLOSION = 91;

    public function encode(Vector3 $pos): array
    {
        return [LevelEventPacket::standardParticle(self::WIND_EXPLOSION, 0, $pos)];
    }
}
