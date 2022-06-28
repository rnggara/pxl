<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Finance_invoice_out_detail extends Model
{
    use SoftDeletes;
    protected $table = 'finance_inv_out_detail';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_inv_out_detail';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
