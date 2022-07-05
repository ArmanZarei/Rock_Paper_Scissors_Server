<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\User;
use App\Policies\GamePolicy;
use App\services\GameService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Game::class => GamePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('quit-queue', function (User $user) {
            $waitingGame = Game::waitingGame()->first();

            if (!$waitingGame)
                return false;

            return $waitingGame->player1->id == $user->id;
        });
    }
}
