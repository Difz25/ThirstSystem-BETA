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
<br>
# Table of contents
- [Examples](#examples)
  - [Get player's thirst](#get-player's-thirst)
  - [Add player's thirst](#add-player's-thirst)

## Examples

### Get player's thirst

You can get player's thirst using the `getThirst`  method. here's is an example:

```php
    public function Example(Player $player): void {
        $thirst = ThirstSystem::getInstance()->getThirst($player);
        if($thirst < 0){
            $player->sendMessage("you're already thirsty");
        }
        if($thirst > 100) {
            $player->sendMessage("you're thirst has been full");
        }
    }
```

### Get player's thirst

You can get player's thirst using the `getThirst`  method. here's is an example:

```php
    public function Example(Player $player): void {
        $thirst = ThirstSystem::getInstance()->getThirst($player);
        if($thirst < 0){
            $player->sendMessage("you're already thirsty");
        }
        if($thirst > 100) {
            $player->sendMessage("you're thirst has been full");
        }
    }
```
