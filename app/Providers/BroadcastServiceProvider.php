<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        // Authenticate the user's personal channel.
        Broadcast::channel('App.User.{userId}', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });

        // Authenticate the user's submissionPage channel.
        Broadcast::channel('submission.*', function ($user) {
            return [
                'username' => $user->username,
            ];
        });

        //require base_path('routes/channels.php');
    }
}
