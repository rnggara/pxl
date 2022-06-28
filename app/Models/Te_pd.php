<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Te_pd extends Model
{
    use SoftDeletes;
    protected $table = 'te_pd';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'te_pd';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
