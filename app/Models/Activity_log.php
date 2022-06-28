<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity_log extends Model
{
    protected $table = 'activity_log';
    protected static $logAttributes = ['first_name', 'last_name', 'email'];


    public function getDescriptionForEvent(string $eventName){
        return "You have $eventName user";
    }
}
