<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
	use SoftDeletes;

	protected $table = 'rms_roles';
	protected $dates = ['deleted_at'];

	public function rprivilege()
	{
		return $this->hasMany('App\Models\RolePrivilege', 'id_rms_roles_divisions', 'id');
	}
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'rms_rolse';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
