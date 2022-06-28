<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Qhse_mv_mom extends Model
{
    use SoftDeletes;
    protected $table = 'qhse_mv_mom';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'qhse_mv_mom';


    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
