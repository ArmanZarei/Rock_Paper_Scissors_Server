<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChooseMovementRequest;
use App\Http\Resources\GameAfterDueTimeResource;
use App\Http\Resources\GameBeforeDueTimeResource;
use App\Models\Game;
use App\services\GameService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function new(Request $request)
    {
        if ($this->gameService->getUserOnGoingGame($request->user()))
            return response()->json(
                ['error' => 'You are already playing a game'],
                Response::HTTP_BAD_REQUEST
            );

        $waitingGame = Game::waitingGame()->first();

        if ($waitingGame && $waitingGame->player1->id == $request->user()->id)
            return response()->json(
                ['error' => 'You are already in the waiting queue'],
                Response::HTTP_BAD_REQUEST
            );

        if ($waitingGame) {
            $waitingGame->player2_id = $request->user()->id;
            $waitingGame->due_time = Carbon::now()->addSeconds(Game::DUE_TIME_SECONDS);
            $waitingGame->update();

            return response()->json([
                'game_id' => $waitingGame->id,
                'started' => true,
            ]);
        } else {
            $game = Game::create(['player1_id' => $request->user()->id]);

            return response()->json([
                'game_id' => $game->id,
                'started' => false,
            ]);
        }
    }

    public function quit(Request $request)
    {
        Game::waitingGame()->where('player1_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Successfully quit the waiting queue']);
    }

    public function status(Request $request, Game $game)
    {
        if (Carbon::now() <= $game->due_time)
            return new GameBeforeDueTimeResource($game, $request->user());
        return new GameAfterDueTimeResource($game);
    }

    public function chooseMove(ChooseMovementRequest $request, Game $game)
    {
        if ($request->user()->id == $game->player1_id)
            $game->player1_move = $request->move;
        else
            $game->player2_move = $request->move;
        $game->update();

        return new GameBeforeDueTimeResource($game, $request->user());
    }
}
