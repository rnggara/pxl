<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Session;

class LoginSuccessful
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->subject = 'login';
        $event->description = $event->user->username . " login";
        Session::flash('login-success', 'Hello'. $event->user->name. ", welcome back");
        activity($event->subject)
            ->by($event->user)
            ->log($event->description);
    }
}
