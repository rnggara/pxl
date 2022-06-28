<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_salary_remarks extends Model
{
    protected $table = 'hrd_salary_remarks';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_salary_remarks';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
