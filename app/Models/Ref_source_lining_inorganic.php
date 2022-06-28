<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ref_source_lining_inorganic extends Model
{
    use SoftDeletes;
    protected $table = 'ref_source_lining_inorganic';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'ref_source_lining_inorganic';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
