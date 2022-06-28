<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Qhse_near_miss extends Model
{
    use SoftDeletes;
    protected $table = 'qhse_near_miss';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'qhse_near_miss';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
