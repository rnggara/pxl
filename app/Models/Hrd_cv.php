<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_cv extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_cv';
    protected $dates =['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_cv';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
