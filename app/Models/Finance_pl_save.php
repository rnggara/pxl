<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Finance_pl_save extends Model
{
    use SoftDeletes;
    protected $table = 'finance_pl_save';
    protected $dates = ['deleted_at', 'archive_time'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_pl_save';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
