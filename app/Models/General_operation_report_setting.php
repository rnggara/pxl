<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class General_operation_report_setting extends Model
{
    use SoftDeletes;
    protected $table = 'general_operation_report_setting';
    protected $dates = ['deleted_at'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_operation_report_setting';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
