<?php

declare(strict_types=1);

namespace XeonCh\Mace;

use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use pocketmine\world\World;
use XeonCh\Mace\entity\WindCharge;

class Main extends PluginBase
{

    public const MACE = "minecraft:mace";
    public const WIND_CHARGE = "minecraft:wind_charge";

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        EntityFactory::getInstance()->register(WindCharge::class, function (World $world, CompoundTag $nbt): WindCharge {
            return new WindCharge(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        }, ['wind_charge', 'minecraft:wind_charge_projectile']);
        self::registerItems();
        $this->getServer()->getAsyncPool()->addWorkerStartHook(function (int $worker): void {
            $this->getServer()->getAsyncPool()->submitTaskToWorker(
                new class extends AsyncTask
                {
                    public function onRun(): void
                    {
                        Main::registerItems();
                    }
                },
                $worker
            );
        });
    }
    public static function registerItems(): void
    {
        $mace = ExtraItems::MACE();
        $wind = ExtraItems::WIND();
        self::registerSimpleItem(self::MACE, $mace, ["mace_xeon", "mace_item"]);
        self::registerSimpleItem(self::WIND_CHARGE, $wind, ["wind_xeon", "wind", "wind_charge_item"]);
    }

    /**
     * @param string[] $stringToItemParserNames
     */
    private static function registerSimpleItem(string $id, Item $item, array $stringToItemParserNames): void
    {
        GlobalItemDataHandlers::getDeserializer()->map($id, fn() => clone $item);
        GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($id));
        foreach ($stringToItemParserNames as $name) {
            StringToItemParser::getInstance()->register($name, fn() => clone $item);
        }
    }
}
