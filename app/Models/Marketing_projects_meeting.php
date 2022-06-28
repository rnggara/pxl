<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Marketing_projects_meeting extends Model
{
    use SoftDeletes;
    protected $table = 'marketing_projects_meetings';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'marketing_projects_meetings';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
