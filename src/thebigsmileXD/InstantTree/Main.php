<?php

/*
 * InstantTree
 * A plugin by thebigsmileXD
 * Creates Trees instantly on placing saplings
 * Remember: there are no Acacia/Dark Oak trees yet
 */
namespace thebigsmileXD\InstantTree;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\generator\object\Tree;
use pocketmine\utils\Random;
use pocketmine\item\Item;
use pocketmine\block\Block;

class Main extends PluginBase implements Listener{
	public $levels = [];

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->levels = $this->getConfig()->get("worlds");
	}

	public function spawnTree(PlayerInteractEvent $event){
		if(($this->getConfig()->get("per-world") == true && !empty($this->levels) && in_array($event->getPlayer()->getLevel()->getName(), $this->levels)) || $this->getConfig()->get("per-world") == false){
			if($event->getItem()->getId() === Item::SAPLING){
				$pos = $event->getBlock()->getSide($event->getFace());
				$blockplacedonid = $pos->getSide(0)->getId();
				if($blockplacedonid === Block::DIRT || $blockplacedonid === Block::GRASS || $blockplacedonid === Block::PODZOL || $blockplacedonid === Block::FARMLAND){
					$level = $event->getBlock()->getLevel();
					Tree::growTree($level, $pos->x, $pos->y, $pos->z, new Random(mt_rand()), $event->getItem()->getDamage());
					if($event->getPlayer()->isSurvival()) $event->getPlayer()->getInventory()->removeItem($event->getItem());
					$event->setCancelled();
				}
				else{
					$event->setCancelled();
					return false;
				}
			}
		}
	}
}