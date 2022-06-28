<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template_files extends Model
{
    use SoftDeletes;
    protected $table = 'template_files';
    protected $dates = ['deleted_at'];
}
