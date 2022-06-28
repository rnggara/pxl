<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Rms_divisions extends Model
{
    use SoftDeletes;
    protected $table = 'rms_divisions';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'rms_divisions';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
