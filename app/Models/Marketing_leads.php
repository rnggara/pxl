<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Marketing_leads extends Model
{
    use SoftDeletes;
    protected $table = 'marketing_leads';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'marketing_leads';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
