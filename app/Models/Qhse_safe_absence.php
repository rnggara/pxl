<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Qhse_safe_absence extends Model
{
    use SoftDeletes;
    protected $table = 'qhse_safe_absence';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id_absence';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'qhse_safe_absence';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
