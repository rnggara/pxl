<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Finance_depreciation extends Model
{
    use SoftDeletes;
    protected $table='finance_depreciation';
    protected $dates=['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_depreciation';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
