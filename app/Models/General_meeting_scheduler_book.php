<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class General_meeting_scheduler_book extends Model
{
    use SoftDeletes;
    protected $table = 'rv_book';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'rv_book';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
