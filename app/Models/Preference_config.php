<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Preference_config extends Model
{
    protected $table = 'preference_config';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'preference_config';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
