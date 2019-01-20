<?php

declare(strict_types=1);

namespace Zedstar16\_8ball;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class Main extends PluginBase {


    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
			case "8ball":
                if(isset($args[0])) {
                    $this->getServer()->getPlayer($sender->getName())->chat("8ball, ".implode(" ", $args));
                    $choices = array(
                        "It is certain" => "a",
                        "It is decidedly so" => "b",
                        "Without a doubt" => "c",
                        "Yes - definitely" => "d",
                        "You may rely on it" => "e",
                        "As I see it, yes" => "f",
                        "Most likely" => "g",
                        "Outlook good" => "h",
                        "Yes" => "i",
                        "Reply hazy, try again" => "j",
                        "Signs point to yes" => "k",
                        "Ask again later" => "l",
                        "Better not tell you now" => "m",
                        "Cannot predict now" => "n",
                        "Concentrate and ask again" => "o",
                        "Don't count on it" => "p",
                        "My reply is no" => "q",
                        "My sources say no" => "r",
                        "Outlook not so good" => "s",
                        "Very doubtful" => "t"
                    );
                    $this->getServer()->broadcastMessage("§6➤ §8[§l§6Bot§r§8] §c8ball§8>§e " . array_rand($choices, 1) );

                  
                } else $sender->sendMessage("I need something to predict!");
            }
        return true;
    }

}
