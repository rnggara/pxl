<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report_pl_prognosis extends Model
{
    use SoftDeletes;
    protected $table = 'report_pl_prognosis';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'report_pl_prognosis';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
