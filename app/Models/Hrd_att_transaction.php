<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hrd_att_transaction extends Model
{
    protected $table = 'hrd_att_transaction';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_att_transaction';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
