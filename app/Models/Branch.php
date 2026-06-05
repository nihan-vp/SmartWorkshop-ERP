<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['workshop_id', 'name', 'address', 'phone', 'email'];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
