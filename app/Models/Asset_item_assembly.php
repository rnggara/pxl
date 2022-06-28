<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset_item_assembly extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'asset_items_assembly';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
    protected $table = 'asset_items_assembly';
    protected $dates = ['deleted_at'];
}
