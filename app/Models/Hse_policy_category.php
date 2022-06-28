<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hse_policy_category extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_category';
    protected $table = 'hse_policy_category';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hse_policy_category';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
