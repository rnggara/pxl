<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Te_testeq extends Model
{
    use SoftDeletes;
    protected $table = 'te_items_testeq';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'te_items_testeq';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
