<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage_user extends Model
{
    use SoftDeletes;
    protected $table = 'storage_user';
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'wh_id'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'storage_user';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
