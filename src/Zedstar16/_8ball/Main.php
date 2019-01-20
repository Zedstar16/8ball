<?php

declare(strict_types=1);

namespace Zedstar16\_8ball;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use Zedstar16\_8ball\tasks\PredictionTask;
class Main extends PluginBase implements Listener {

    /**@var array*/
    public $cooldown = [];
    /**@var array*/
    public $choices = array(
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

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->saveDefaultConfig();
    }


    public function onChat(PlayerChatEvent $event){
        $pn = $event->getPlayer()->getName();

        if(strpos($event->getMessage(), $this->getConfig()->get("bot-prefix")) !== false){
            $ct = floatval($this->getConfig()->get("prediction-cooldown"));
            if((!isset($this->cooldown[$pn])) || (($this->cooldown[$pn] + $ct - time() <= 0))){
                $this->cooldown[$pn] = time();
                //there is a delayed task because otherwise the prediction will send before the players message
                $this->getScheduler()->scheduleDelayedTask(new PredictionTask($this), 2);
            }else{
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(C::RED . "Don't try and predict anything too quickly! Wait ".C::WHITE . ($this->cooldown[$pn] + $ct - time()) . " seconds ".C::RED."before trying to make another prediction ;)");
            }
        }
    }


    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
			case "8ball":
                if(isset($args[0])) {
                    $this->getServer()->getPlayer($sender->getName())->chat($this->getConfig()->get("bot-prefix") . " " . implode(" ", $args));
                } else $sender->sendMessage("I need something to predict!");
            }
        return true;
    }

}

