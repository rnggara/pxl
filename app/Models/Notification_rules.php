<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Notification_rules extends Model
{
    protected $table = 'notification_rules';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'notification_rules';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
