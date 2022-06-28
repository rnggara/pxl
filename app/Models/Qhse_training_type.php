<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qhse_training_type extends Model
{
    use SoftDeletes;
    protected $table = 'qhse_training_type';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'qhse_training_type';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
