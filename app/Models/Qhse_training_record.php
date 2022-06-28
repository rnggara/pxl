<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qhse_training_record extends Model
{
    use SoftDeletes;
    protected $table = 'qhse_training_record';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'qhse_training_record';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
