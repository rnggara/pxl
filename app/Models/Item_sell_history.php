<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item_sell_history extends Model
{
    use SoftDeletes;
    protected $table = 'item_sell_history';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'item_sell_history';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
