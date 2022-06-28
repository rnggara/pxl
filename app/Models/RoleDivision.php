<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class RoleDivision extends Model
{
	use SoftDeletes;

	protected $table = 'rms_roles_divisions';
	protected $dates = ['deleted_at'];

	public function privilege()
	{
		return $this->hasMany('App\Models\RolePrivilege', 'id_rms_roles_divisions', 'id');
	}

	public function role()
	{
		return $this->belongsTo('App\Models\Role', 'id_rms_roles', 'id');
	}

	public function division()
	{
		return $this->belongsTo('App\Models\Division', 'id_rms_divisions', 'id');
	}

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'rms_roles_division';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
