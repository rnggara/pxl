<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Te_instrumentation extends Model
{
    use SoftDeletes;
    protected $table = 'te_items_instrument';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'te_items_instrument';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
