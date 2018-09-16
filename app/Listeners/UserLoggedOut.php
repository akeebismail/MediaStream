<?php

namespace App\Listeners;

use App\Actibity;
use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoggedOut
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        if (!isset($event->user)){
            return;
        }
        Actibity::query()->create([
            'subject_id'   => $event->user->id,
            'ip_address'   => getRequestIpAddress(),
            'user_agent'   => getRequestUserAgent(),
            'country'      => getRequestCountry(),
            'subject_type' => 'App\User',
            'name'         => 'logged_out_user',
            'user_id'      => $event->user->id,
        ]);
    }
}
