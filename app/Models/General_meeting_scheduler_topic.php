<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class General_meeting_scheduler_topic extends Model
{
    use SoftDeletes;
    protected $table = 'rv_topic';
    protected $primaryKey = 'id_topic';
    protected $dates = ['deleted_at'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'rv_topic';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

}
