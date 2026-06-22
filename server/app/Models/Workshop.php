<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'gstin',
        'logo',
        'trial_ends_at',
        'subscription_status',
        'alert_message',
        'alert_expires_at',
        'restrict_features_on_expiry',
        'admin_extend_allowed',
        'trial_extended_count',
        'alert_dismissed',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'alert_expires_at' => 'datetime',
        'restrict_features_on_expiry' => 'boolean',
        'admin_extend_allowed' => 'boolean',
        'alert_dismissed' => 'boolean',
    ];

    public function isTrial(): bool
    {
        return $this->subscription_status === 'trial' || $this->subscription_status === 'training';
    }

    public function isTrialExpired(): bool
    {
        if (in_array($this->subscription_status, ['suspended', 'fix', 'fixed'])) {
            return false;
        }
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function isActive(): bool
    {
        return !$this->isSuspended() && (!$this->isTrialExpired() || !$this->restrict_features_on_expiry);
    }

    public function isSuspended(): bool
    {
        return $this->subscription_status === 'suspended';
    }

    public function getTrialDaysRemaining(): int
    {
        if (!$this->trial_ends_at) {
            return 0;
        }
        return max(0, (int) now()->startOfDay()->diffInDays($this->trial_ends_at->startOfDay(), false));
    }

    /**
     * Returns the current "day number" since this workshop was created.
     * e.g. created today = Day 1, created 30 days ago = Day 31
     */
    public function getSubscriptionDay(): int
    {
        return max(0, (int) $this->created_at->startOfDay()->diffInDays(now()->startOfDay()));
    }

    /**
     * Returns the total duration in days of the CURRENT subscription period.
     * Calculated from the last key activation date to trial_ends_at.
     * Falls back to created_at if no key activation date is available.
     */
    public function getTotalDurationDays(): int
    {
        if (!$this->trial_ends_at) {
            return 0;
        }
        // Use the most recently activated key's date as the subscription start
        $lastKey = $this->productKeys()->orderBy('used_at', 'desc')->first();
        $start = $lastKey?->used_at ?? $this->created_at;
        return max(0, (int) $start->startOfDay()->diffInDays($this->trial_ends_at->startOfDay()));
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function productKeys(): HasMany
    {
        return $this->hasMany(ProductKey::class, 'used_by_workshop_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function warranties(): HasMany
    {
        return $this->hasMany(Warranty::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function billTemplates(): HasMany
    {
        return $this->hasMany(BillTemplate::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
