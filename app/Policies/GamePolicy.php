<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Game $game)
    {
        return ($game?->player1->id == $user->id) || ($game?->player2->id == $user->id);
    }

    public function choose_move(User $user, Game $game)
    {
        $isPlayerOfGame = ($game?->player1->id == $user->id) || ($game?->player2->id == $user->id);

        return $isPlayerOfGame && Carbon::now() <= $game->due_time;
    }
}
