<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class General_covid_employee extends Model
{
    use SoftDeletes;
    protected $table = 'general_covid_employee';
    protected $dates =['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_covid_employee';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
