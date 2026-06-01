<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToWorkshop;

class Vehicle extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['customer_id', 'make', 'model', 'year', 'plate_number', 'color', 'workshop_id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->make} {$this->model} ({$this->plate_number})";
    }
}
