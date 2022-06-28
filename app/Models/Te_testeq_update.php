<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Te_testeq_update extends Model
{
    use SoftDeletes;
    protected $table = 'te_items_testeq_update';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'te_items_testeq_update';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
