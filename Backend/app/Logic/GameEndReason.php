<?php
namespace App\Logic;

enum GameEndReason : string
{
    case QUIT = "quit the game.";
    case ELIMINATION = "no more stones left.";
    case INACTIVE = "not moved in time.";
    case BLOCKED = "nowhere to move left.";
}
