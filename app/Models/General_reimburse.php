<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class General_reimburse extends Model
{
    use SoftDeletes;
    protected $table ='general_reimburse';
    protected $dates=['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_reimburse';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
