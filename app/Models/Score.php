<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['player_name', 'github_username', 'total_time', 'levels_completed'];
}
