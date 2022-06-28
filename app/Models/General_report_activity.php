<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class General_report_activity extends Model
{
    use SoftDeletes;
    protected $table ='general_report_activity';
    protected $dates=['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_report_activity';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}