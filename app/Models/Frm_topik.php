<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Frm_topik extends Model
{
    use SoftDeletes;
    protected $table = 'frm_topik';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id_topik';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'frm_topik';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
