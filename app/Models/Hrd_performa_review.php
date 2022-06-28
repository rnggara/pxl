<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_performa_review extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_performa_review';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_performa_review';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
