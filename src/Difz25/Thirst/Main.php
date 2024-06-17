<?php

namespace Difz25\Thirst;

use JsonException;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Difz25\Thirst\Listener\TagResolveEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\scheduler\ClosureTask;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;
use Ifera\ScoreHud\ScoreHud;

class ThirstSystem extends PluginBase implements Listener {

    private static ThirstSystem $instance;
    public Config $thirst;
    
    public array $inventory = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $player = Player::class;
        $this->thirst = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML, [
            "Thirst" => 100,
        ]);
        if (class_exists(ScoreHud::class)) {
            $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
                closure: function (): void {
                    foreach ($this->getServer()->getOnlinePlayers() as $player) {
                        if (!$player->isOnline()) {
                            continue;
                        }

                        (new PlayerTagsUpdateEvent($player, [
                            new ScoreTag("thirstsystem.thirst", $this->Format($this->getThirst($player))),
                        ]))->call();
                    }
                }
            ), 1);
            $this->getServer()->getPluginManager()->registerEvents(new TagResolveEvent($this), $this);
        }
    }
    
    public function Format($num): string {
            if(!is_numeric($num)) return  '0%';
        $format = number_format((int) $num, 2, ',', '.');
        return '%' . $format;
    }
    
    public function getPlayerData(string|Player $player): ?Config {
        if ($player instanceof Player) {
            if (is_file($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml")) {
                return new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
            }
        } else {
            if (is_file($this->getDataFolder() . "players/" . strtolower($player) . ".yml")) {
                return new Config($this->getDataFolder() . "players/" . strtolower($player) . ".yml", Config::YAML);
            }
        }
        return null;
    }
    
    public function getThirst(string|Player $player): ?int {
        if (($data = $this->getPlayerData($player)) !== null) {
            return $data->getAll()["Thirst"];
        }
    
        return null;
    }

    /**
     * @throws JsonException
     */
    public function reduceThirst(string|Player $player, int $reduce): bool {
        if (($data = $this->getPlayerData($player)) !== null){
            $thirst = $data->get("Thirst");
            $thirst -= $reduce;
            $data->set("Thirst", $thirst);
            $data->save();
            return true;
        }
    
    return false;
    }

    /**
     * @throws JsonException
     */
    public function addThirst(string|Player $player, int $add): bool {
        if (($data = $this->getPlayerData($player)) !== null) {
            $thirst = $data->get("Thirst");
            $thirst += $add;
            $data->set("Thirst", $thirst);
            $data->save();
            return true;
        }

        return false;
    }

    /**
     * @throws JsonException
     */
    public function onPlayerMove(PlayerMoveEvent $event): bool {
        $player = $event->getPlayer();
        $username = $player->getName();
        if(($data = $this->getPlayerData($player)) !== null){
            $this->reduceThirst($player, 0.01);
            if($this->getThirst($player) < 0){
                $currentHealth = $player->getHealth();
                $healthLost = $currentHealth - 1;
                $player->setHealth($healthLost);
                if($player->isAlive() && $currentHealth > 0){
                    $this->inventory[$username] = $player->getInventory()->getContents();
                }
                if(!$player->isAlive() && $currentHealth < 0){
                    if (isset($this->inventory[$username])) {
                        $player->getInventory()->setContents($this->inventory[$username]);
                        unset($this->inventory[$username]);
                    }
                }
            }
            $data->save();
            return true;
        }
        
        return false;
    }

    /**
     * @throws JsonException
     */
    public function onPlayerInteract(PlayerInteractEvent $event): bool {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $potion = $item->getTypeId() == VanillaItems::POTION();
        if ($potion == PotionType::WATER()) {
            if (($data = $this->getPlayerData($player)) !== null) {
               $this->addThirst($player, 10);
                $data->save();
                return true;
            }
        }
        
        return false;
    }

    /**
     * @throws JsonException
     */
    public function onPlayerJoin(PlayerJoinEvent $event): bool
    {
        $player = $event->getPlayer();
        if (($data = $this->getPlayerData($player)) === null) {
            $data->set("Thirst", 100);
            $data->save();
            return true;
        }

        return false;
    }

    /**
     * @throws JsonException
     */
    public function onPlayerQuit(PlayerQuitEvent $event): bool {
        $player = $event->getPlayer();
        if (($data = $this->getPlayerData($player)) !== null) {
            $data->save();
            return true;
        }

        return false;
    }
    
    public static function getInstance(): self {
        return self::$instance;
    }

    public function onLoad(): void {
        self::$instance = $this;
    }
}
