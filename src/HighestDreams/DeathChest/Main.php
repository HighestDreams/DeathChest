<?php
declare(strict_types=1);

namespace HighestDreams\DeathChest;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest as TileChest;
use pocketmine\tile\Tile;

class Main extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();

        /* Honestly i hate using 1 code in 2 line just for a little stupid change */
        foreach ([0, 1] as $num) {
            $player->getLevel()->setBlock(new Vector3($player->getX() + $num, $player->getY(), $player->getZ()), Block::get(Block::CHEST));
        }

        $firstChest = Tile::createTile(Tile::CHEST, $player->getLevel(), TileChest::createNBT($player->asVector3()));
        $secondChest = Tile::createTile(Tile::CHEST, $player->getLevel(), TileChest::createNBT(new Vector3($player->getX() + 1, $player->getY(), $player->getZ())));

        if ($firstChest instanceof TileChest) {
            if ($secondChest instanceof TileChest) {
                $firstChest->pairWith($secondChest);
            }
            foreach ($player->getDrops() as $drop) {
                $firstChest->getInventory()->addItem($drop);
            }
        }
        $event->setDrops([]);
        $player->sendMessage("Your items saved in your loot chest! go back and get your items before other players find your loot chest!");
    }
}
