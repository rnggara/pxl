<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Qhse_safe_mom extends Model
{
    use SoftDeletes;
    protected $table = 'qhse_safe_mom';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'qhse_safe_mom';

    protected $primaryKey = 'id_mom';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
