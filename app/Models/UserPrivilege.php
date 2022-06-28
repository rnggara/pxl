<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class UserPrivilege extends Model
{
	use SoftDeletes;

	protected $table = 'rms_users_privileges';
	protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'id_users', 'id');
	}

	public function module()
	{
		return $this->belongsTo('App\Models\Module', 'id_rms_modules', 'id');
	}

	public function action()
	{
		return $this->belongsTo('App\Models\Action', 'id_rms_actions', 'id');
	}

    // use LogsActivity;

    // protected static $logAttributes = ['*'];

    // protected static $logOnlyDirty = true;

    // protected static $logName = 'rms_users_privileges';

    // public function getDescriptionForEvent(string $eventName): string {
    //     return "This model has been $eventName";
    // }
}
