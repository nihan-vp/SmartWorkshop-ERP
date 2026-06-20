<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkshop;

class Service extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['name', 'description', 'price', 'duration_minutes', 'category', 'workshop_id'];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
