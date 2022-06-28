<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pref_activity_label extends Model
{
    // use SoftDeletes;
    protected $table = 'pref_activity_label';
    // protected $dates = ['deleted_at'];

    // use LogsActivity;

    // protected static $logAttributes = ['*'];

    // protected static $logOnlyDirty = true;

    // protected static $logName = 'pref_activity_label';

    // public function getDescriptionForEvent(string $eventName): string {
    //     return "This model has been $eventName";
    // }
}
