<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver_Model extends Model
{
    protected $table = 'general_drivers';
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_drivers';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
