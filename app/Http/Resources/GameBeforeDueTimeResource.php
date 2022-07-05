<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GameBeforeDueTimeResource extends JsonResource
{
    private User $player;
    public function __construct($resource, $player)
    {
        parent::__construct($resource);

        $this->player = $player;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'is_started' => $this->is_started,
            'id' => $this->id,
            'player1' => $this->player1,
            'player2' => $this->player2,
            'due_time' => $this->due_time,
            'your_move' => $this?->player1->id == $this->player->id ? $this->player1_move : $this->player2_move,
        ];
    }
}
