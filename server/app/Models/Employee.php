<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToWorkshop;
use App\Traits\BelongsToBranch;

class Employee extends Model
{
    use BelongsToWorkshop, BelongsToBranch;

    protected $fillable = ['name', 'phone', 'email', 'role', 'salary', 'join_date', 'status', 'workshop_id'];

    protected $casts = [
        'salary' => 'decimal:2',
        'join_date' => 'date',
    ];

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }
}
