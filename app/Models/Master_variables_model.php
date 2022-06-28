<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master_variables_model extends Model
{
    use SoftDeletes;
    protected $table = 'master_variables';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'master_variables';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
