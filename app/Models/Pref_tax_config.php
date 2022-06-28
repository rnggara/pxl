<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Pref_tax_config extends Model
{
    use SoftDeletes;
    protected $table = 'pref_tax_config';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'pref_tax_config';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
