<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_employee_loan extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_employee_loan';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_employee_loan';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
