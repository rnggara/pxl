<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_loan_payment extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_loan_payment';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_loan_payment';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
