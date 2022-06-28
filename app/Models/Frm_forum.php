<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Frm_forum extends Model
{
    use SoftDeletes;
    protected $table = 'frm_forum';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'frm_forum';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
