<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset_wh extends Model
{
    use SoftDeletes;
    protected $table = 'asset_wh';
    protected $dates = ['deleted_at'];
}
