<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Finance_invoice_out extends Model
{
    use SoftDeletes;
    protected $table = 'finance_inv_out';
    protected $dates = ['deleted_at'];

    use LogsActivity;
    protected $primaryKey = 'id_inv';

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_invoice_out';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
