<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Finance_business_master_partner extends Model
{
    use SoftDeletes;

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_business_master_partners';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been $eventName";
    }

    protected $table = 'finance_business_master_partners';
    protected $dates = ['deleted_at'];
}
