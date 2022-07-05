<?php


namespace App\services;


use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;

class GameService
{
    public function getUserOnGoingGame(User $user): Game|null
    {
        return Game::where('due_time', '>=', Carbon::now())
            ->where(function ($query) use ($user) {
                return $query->where('player1_id', $user->id)->orWhere('player2_id', $user->id);
            })
            ->first();
    }
}
