<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = ['endpoint', 'method', 'user_id', 'response_time'];
}
