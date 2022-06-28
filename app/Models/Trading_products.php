<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Trading_products extends Model
{
    use SoftDeletes;
    protected $table = 'trading_products';
    protected $dates = ['deteled_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'trading_products';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
