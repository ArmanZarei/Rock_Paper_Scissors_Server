<?php

namespace App\Models;

use App\Constants\GameMovementConstants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    const DUE_TIME_SECONDS = 10;

    use HasFactory;

    protected $guarded = [];

    public function player1()
    {
        return $this->belongsTo(User::class);
    }

    public function player2()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsStartedAttribute()
    {
        return $this->player1_id && $this->player2_id && $this->due_time;
    }

    public function scopeWaitingGame($query)
    {
        return $query->where('player2_id', null)->where('due_time', null);
    }

    public function getWinnerAttribute()
    {
        if (
            $this->due_time == null ||
            Carbon::now() <= $this->due_time ||
            ($this->player1_move == null && $this->player2_move == null)
        ) {
            return null;
        }

        $result = $this->moveBeatsOtherMove($this->player1_move, $this->player2_move);

        switch ($result) {
            case -1:
                return $this->player2;
            case 1:
                return $this->player1;
            case 0:
                return null;
        }

        return null;
    }

    private function moveBeatsOtherMove($move1, $move2) {
        if ($move1 == $move2)
            return 0;

        if ($move1 == null)
            return -1;

        if ($move2 == null)
            return 1;

        if (
            ($move1 == GameMovementConstants::ROCK && $move2 == GameMovementConstants::SCISSORS) ||
            ($move1 == GameMovementConstants::SCISSORS && $move2 == GameMovementConstants::PAPER) ||
            ($move1 == GameMovementConstants::PAPER && $move2 == GameMovementConstants::ROCK)
        )
            return 1;

        return -1;
    }
}
