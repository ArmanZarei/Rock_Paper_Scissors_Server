<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameAfterDueTimeResource extends JsonResource
{
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
            'player1_move' => $this->player1_move,
            'player2_move' => $this->player2_move,
            'winner' => $this->winner,
            'due_time' => $this->due_time
        ];
    }
}
