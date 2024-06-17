<?php
declare(strict_types = 1);

namespace Difz25\Thirst\Listener;

use Difz25\Thirst\ThirstSystem;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class EventListener implements Listener{

    /** @var ThirstSystem */
    private ThirstSystem $plugin;

    public function __construct(ThirstSystem $plugin){
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->sendTags($player);
    }

    private function sendTags(Player $player): void{
        (new PlayerTagsUpdateEvent($player, [
            new ScoreTag("thirstsystem.thirst", $this->plugin->Format($this->plugin->getThirst($player)))
        ]))->call();
    }
}