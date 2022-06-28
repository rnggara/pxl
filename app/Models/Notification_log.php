<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification_log extends Model
{
    use SoftDeletes;
    protected $table = 'notification_log';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'notification_log';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
