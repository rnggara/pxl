<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Pref_activity_point extends Model
{
    use SoftDeletes;
    protected $table = 'pref_activity_point';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'pref_activity_point';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
