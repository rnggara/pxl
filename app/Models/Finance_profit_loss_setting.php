<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Finance_profit_loss_setting extends Model
{
    protected $table = 'finance_profit_loss_setting';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_profit_loss_setting';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
