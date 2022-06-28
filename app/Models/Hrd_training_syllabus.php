<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_training_syllabus extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_training_syllabus';
    protected $dates =['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_training_syllabus';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
