<?php

namespace App\Models;

use App\Traits\BelongsToWorkshop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePayment extends Model
{
    use BelongsToWorkshop;

    protected $fillable = [
        'workshop_id',
        'employee_id',
        'date',
        'amount',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
