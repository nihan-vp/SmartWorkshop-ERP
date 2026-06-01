<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\BelongsToWorkshop;

class WorkOrder extends Model
{
    use BelongsToWorkshop;

    protected $fillable = [
        'order_number', 'customer_id', 'vehicle_id', 'employee_id',
        'description', 'status', 'priority', 'estimated_cost', 'actual_cost',
        'start_date', 'end_date', 'workshop_id'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->count();
        return 'WO-' . $year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
