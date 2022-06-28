<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Finance_util_master extends Model
{
    use SoftDeletes;
    protected $table = 'finance_util_master';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_util_master';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
