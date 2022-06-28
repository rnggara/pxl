<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users_zakat extends Model
{
    use SoftDeletes;
    protected $table = 'user_zakat';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'user_zakat';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
