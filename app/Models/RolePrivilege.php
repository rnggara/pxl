<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class RolePrivilege extends Model
{
	use SoftDeletes;

	protected $table = 'rms_roles_privileges';
	protected $dates = ['deleted_at'];

	public function roleDiv()
	{
		return $this->belongsTo('App\Models\RoleDivision', 'id', 'id_rms_roles_divisions');
	}

	public function module()
	{
		return $this->belongsTo('App\Models\Module', 'id', 'id_rms_modules');
	}

	public function action()
	{
		return $this->belongsTo('App\Models\Action', 'id', 'id_rms_actions');
	}

    // use LogsActivity;

    // protected static $logAttributes = ['*'];

    // protected static $logOnlyDirty = true;

    // protected static $logName = 'rms_roles_privileges';

    // public function getDescriptionForEvent(string $eventName): string {
    //     return "This model has been $eventName";
    // }
}
