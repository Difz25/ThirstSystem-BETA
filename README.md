# ThirstSystem
ThirstSystem was developed by [Difz25](https://github.com/Difz25)

# Depend
| Authors | Github | Plugin |
|---------|--------|-----|
| Ifera / Tayyab R. | [Ifera](https://github.com/Ifera) | [ScoreHud](https://github.com/Ifera/ScoreHud) |

# Bug / Issues
- Its beta no issues
- 
# ScoreHud Support
| Scoretag | Feature |
| - | - |
| `{thirstsystem.thirst}` | To show player thirst |

# Table of contents
- [Examples](#examples)
  - [Get player's thirst](#get-player's-thirst)
  - [Add player's thirst](#add-player's-thirst)
  - [Reduce player's thirst]($reduce-player's-thirst)

## Examples

### Get player's thirst

You can get player's thirst using the `getThirst`  method. here's is an example:

```php
    public function Example(string|Player $player): void {
        $thirst = ThirstSystem::getInstance()->getThirst($player);
        if($thirst < 0){
            $player->sendMessage("you're already thirsty");
        }
        if($thirst > 100) {
            $player->sendMessage("you're thirst has been full");
        }
    }
```

### Add player's thirst

You can add player's thirst using the `addThirst`  method. here's is an example:

```php
    public function Example(string|Player $player, int $amount): void {
        $thirst = ThirstSystem::getInstance()->addThirst($player, $amount);
        $player->sendMessage("you're earned" . $thirst);
    }
```

### Reduce player's thirst

You can reduce player's thirst using the `reduceThirst`  method. here's is an example:

```php
    public function Example(string|Player $player, int $amount): void {
        $thirst = ThirstSystem::getInstance()->reduceThirst($player, $amount);
        $player->sendMessage("you're thirst deacreased " $amount);
    }
```
