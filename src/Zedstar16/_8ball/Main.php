<?php

declare(strict_types=1);

namespace Zedstar16\_8ball;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use Zedstar16\_8ball\tasks\PredictionTask;
class Main extends PluginBase implements Listener {

    /**@var array*/
    public $cooldown = [];
    /**@var array*/
    public $sendresponseto = [];
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


    /**
     * @param PlayerChatEvent $event
     * @event Priority HIGH
     * @ignoreCancelled True
     */
    public function onChat(PlayerChatEvent $event){
        $prefix = $this->getConfig()->get("bot-prefix");
        $prefixlength = strlen($prefix);
        if(substr($event->getMessage(), 0, $prefixlength) == $prefix) {
            $pn = $event->getPlayer()->getName();
            $ct = floatval($this->getConfig()->get("prediction-cooldown"));
            if((!isset($this->cooldown[$pn])) || (($this->cooldown[$pn] + $ct - time() <= 0))){
                $this->cooldown[$pn] = time();
                //there is a delayed task because otherwise the prediction will send before the players message
                $this->getScheduler()->scheduleDelayedTask(new PredictionTask($this), 2);
                if($this->getConfig()->get("send-message-as") == "message") {
                    $this->sendresponseto[$pn] = $pn;
                    $event->setCancelled();
                }
            }else{
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(C::RED . "Don't try and predict anything too quickly! Wait ".C::WHITE . ($this->cooldown[$pn] + 10 - time()) . " seconds ".C::RED."before trying to make another prediction ;)");
            }
        }
    }

}
