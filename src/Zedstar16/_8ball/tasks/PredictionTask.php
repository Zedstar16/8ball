<?php
/**
 * Created by PhpStorm.
 * User: ZZach
 * Date: 20/01/2019
 * Time: 12:44
 */

namespace Zedstar16\_8ball\tasks;

use pocketmine\scheduler\Task;
use Zedstar16\_8ball\Main;

class PredictionTask extends Task
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        $msg = $this->plugin->getConfig()->get("bot-message");
        $msg = str_replace("{prediction}", array_rand($this->plugin->choices, 1), $msg);
        $this->plugin->getServer()->broadcastMessage($msg);
    }

}