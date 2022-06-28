<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketing_client_category extends Model
{
    use SoftDeletes;
    protected $table = 'marketing_client_category';
    protected $dates = ['deleted_at'];
}
