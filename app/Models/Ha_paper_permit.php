<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Ha_paper_permit extends Model
{
    use SoftDeletes;
    protected $table='ha_paper_permit';
    protected $dates=['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'ha_paper_permit';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
