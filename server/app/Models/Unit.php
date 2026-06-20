<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToWorkshop;

class Unit extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['name', 'symbol', 'description', 'workshop_id'];
}
