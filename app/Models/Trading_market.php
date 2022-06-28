<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Trading_market extends Model
{
    use SoftDeletes;
    protected $table = 'trading_markets';
    protected $dates = ['deteled_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'trading_markets';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
