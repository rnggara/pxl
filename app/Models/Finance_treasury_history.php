<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Finance_treasury_history extends Model
{
    use SoftDeletes;
    protected $table = 'finance_treasure_history';
    protected $fillable = ['id_treasure','date_input','description','IDR','USD','PIC'];
    protected $dates = ['deleted_at'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'finance_treasure_history';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

}
