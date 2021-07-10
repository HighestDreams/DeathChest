<?php

declare(strict_types=1);

namespace HighestDreams\DeathChest;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest as TileChest;
use pocketmine\tile\Tile;

class Main extends PluginBase implements Listener {

    public function onEnable()
    {
        $this->getLogger()->warning("Kos server nagaidam (Plugin by HighestDreams) Telegram channel => @ErrorByNightGuard");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin (PlayerJoinEvent $event) {
        echo ' Joined';
    }

    public function onDeath (PlayerDeathEvent $event) { // Pedaret mord...
        $player = $event->getPlayer();
        $drops = $player->getDrops();

        foreach ([0, 1] as $num) {
            $player->getLevel()->setBlock(new Vector3($player->getX() + $num, $player->getY(), $player->getZ()), Block::get(Block::CHEST));
        }

        $tile = Tile::createTile(Tile::CHEST, $player->getLevel(), TileChest::createNBT(new Vector3($player->getX(), $player->getY(), $player->getZ())));
        $tile2 = Tile::createTile(Tile::CHEST, $player->getLevel(), TileChest::createNBT(new Vector3($player->getX() + 1, $player->getY(), $player->getZ())));

        if ($tile instanceof TileChest) {
            if ($tile2 instanceof TileChest) {
                $tile->pairWith($tile2);
            }
            foreach ($drops as $drop) {
                $tile->getInventory()->addItem($drop);
            }
        }
        $event->setDrops([]);
        $player->sendMessage("Item haye shoma to chest save shod! (Plugin By HighestDreams)");
    }
}
