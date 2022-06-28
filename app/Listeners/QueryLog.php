<?php

namespace App\Listeners;

use Iluminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class QueryLog
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
     * @param  DB  $event
     * @return void
     */
    public function handle(DB $event)
    {
        DB::connection()->enableQueryLog();

        $query = DB::getQueryLog();

        $event->subject = 'db query';
        $event->description = end($query);
        activity($event->subject)
            ->by($event->user)
            ->log($event->description);
    }
}
