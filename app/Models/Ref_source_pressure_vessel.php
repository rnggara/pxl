<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ref_source_pressure_vessel extends Model
{
    use SoftDeletes;
    protected $table = 'ref_source_pressure_vessel';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'ref_source_pressure_vessel';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
