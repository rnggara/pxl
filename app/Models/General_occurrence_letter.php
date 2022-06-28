<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class General_occurrence_letter extends Model
{
    use SoftDeletes;
    protected $table = 'general_occurrence_letter';
    protected $dates = ['deleted_at'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_occurrence_letter';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
