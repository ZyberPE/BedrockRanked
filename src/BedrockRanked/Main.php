<?php

namespace BedrockRanked;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use pocketmine\block\VanillaBlocks;

class Main extends PluginBase{

    public function onEnable(): void{
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{

        if(!$sender instanceof Player){
            return true;
        }

        if(!$sender->hasPermission("bedrock.rank")){
            $sender->sendMessage($this->getConfig()->get("messages")["no-permission"]);
            return true;
        }

        $target = $sender->getTargetBlock(5);

        if($target === null || $target->getTypeId() !== VanillaBlocks::BEDROCK()->getTypeId()){
            $sender->sendMessage($this->getConfig()->get("messages")["must-look-bedrock"]);
            return true;
        }

        $world = $sender->getWorld();
        $pos = $target->getPosition();

        $world->setBlock($pos, VanillaBlocks::AIR());

        $item = VanillaBlocks::BEDROCK()->asItem();

        $inventory = $sender->getInventory();

        if($inventory->canAddItem($item)){
            $inventory->addItem($item);
            $sender->sendMessage($this->getConfig()->get("messages")["success"]);
        }else{
            $world->dropItem($pos, $item);
            $sender->sendMessage($this->getConfig()->get("messages")["dropped"]);
        }

        return true;
    }
}
