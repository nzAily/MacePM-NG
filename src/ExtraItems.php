<?php

declare(strict_types=1);

namespace XeonCh\Mace;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\ToolTier;
use pocketmine\utils\CloningRegistryTrait;
use XeonCh\Mace\item\Mace;
use XeonCh\Mace\item\Wind;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static Mace MACE()
 * @method static Wind WIND()
 */
final class ExtraItems
{
    use CloningRegistryTrait;

    private function __construct()
    {
    }

    protected static function register(string $name, Item $item): void
    {
        self::_registryRegister($name, $item);
    }

    /**
     * @return Item[]
     * @phpstan-return array<string, Item>
     */
    public static function getAll(): array
    {
        //phpstan doesn't support generic traits yet :(
        /** @var Item[] $result */
        $result = self::_registryGetAll();
        return $result;
    }

    protected static function setup(): void
    {
        $id = ItemTypeIds::newId();
        $wid = ItemTypeIds::newId();
        self::register("mace", new Mace(new ItemIdentifier($id), "Mace", ToolTier::NETHERITE()));
        self::register("wind", new Wind(new ItemIdentifier($wid), "Wind"));
    }
}