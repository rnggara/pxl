<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Marketing_bp_detail extends Model
{
    use SoftDeletes;
    protected $table = 'marketing_bp_detail';
    protected $fillable = ['id_detail','id_main','prj_code','item_name','request_amount','actual_amount','currency'];
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'marketing_bp_detail';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
