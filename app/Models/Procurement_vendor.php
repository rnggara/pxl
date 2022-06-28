<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Procurement_vendor extends Model
{
    use SoftDeletes;
    protected $table = 'asset_organization';
    protected $dates = ['deteled_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'procurement_vendor';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
