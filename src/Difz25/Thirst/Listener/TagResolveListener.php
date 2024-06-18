<?php
declare(strict_types = 1);

namespace Difz25\Thirst\Listener;

use Ifera\ScoreHud\event\TagsResolveEvent;
use Difz25\Thirst\ThirstSystem;
use pocketmine\event\Listener;
use function count;
use function explode;

/**
* @property $eco
* @property ThirstSystem $plugin
*/
class TagResolveListener implements Listener{

    private ThirstSystem $plugin;

    public function __construct(ThirstSystem $plugin){
        $this->plugin = $plugin;
    }

    public function onTagResolve(TagsResolveEvent $event): void {
        $tag = $event->getTag();
        $player = $event->getPlayer();
        $tags = explode('.', $tag->getName(), 2);
        $value = "";

        if($tags[0] !== 'thirstsystem' || count($tags) < 2){
            return;
        }

        if ($tags[1] == "thirst") {
            $value = $this->plugin->Format($this->plugin->getThirst($player));
        }
        $tag->setValue(($value));
    }
}