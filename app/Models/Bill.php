<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Traits\BelongsToWorkshop;
use App\Traits\BelongsToBranch;

class Bill extends Model
{
    use BelongsToWorkshop, BelongsToBranch;

    protected $fillable = [
        'bill_number', 'customer_id', 'vehicle_id', 'subtotal', 'tax',
        'discount', 'total', 'amount_paid', 'payment_method', 'payment_status', 'notes', 'bill_date', 'workshop_id'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'bill_date' => 'date',
    ];

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->total - $this->amount_paid);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    public function warranty(): HasOne
    {
        return $this->hasOne(Warranty::class);
    }

    public static function generateBillNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->count();
        return 'SSW-' . $year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
