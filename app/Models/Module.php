<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Module extends Model
{
	use SoftDeletes;

	protected $table = 'rms_modules';
	protected $dates = ['deleted_at'];

	public function privilege()
	{
		return $this->hasMany('App\Models\UserPrivilege', 'id_rms_modules', 'id');
	}

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'rms_modules';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
