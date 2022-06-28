<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Te_swt extends Model
{
    use SoftDeletes;
    protected $table = 'te_swt';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'te_swt';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
