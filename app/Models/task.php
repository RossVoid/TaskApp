<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $table = "tasks";
    protected $fillable =
    [
        'user_id',
        'task_title',
        'task_description',
        'task_status',
    ];
}