<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\BelongsToWorkshop;

class Salary extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['employee_id', 'amount', 'advance_deduction', 'month', 'year', 'payment_date', 'payment_method', 'status', 'workshop_id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
