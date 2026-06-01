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
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'alert_expires_at' => 'datetime',
    ];

    public function isTrial(): bool
    {
        return $this->subscription_status === 'trial';
    }

    public function isTrialExpired(): bool
    {
        if ($this->subscription_status === 'suspended') {
            return false;
        }
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function isActive(): bool
    {
        return !$this->isSuspended() && !$this->isTrialExpired();
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
        return max(0, (int) now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Returns the current "day number" since this workshop was created.
     * e.g. created today = Day 1, created 30 days ago = Day 31
     */
    public function getSubscriptionDay(): int
    {
        return (int) $this->created_at->startOfDay()->diffInDays(now()->startOfDay()) + 1;
    }

    /**
     * Returns the total duration in days of the subscription/trial period.
     * Calculated from creation date to trial_ends_at.
     */
    public function getTotalDurationDays(): int
    {
        if (!$this->trial_ends_at) {
            return 0;
        }
        return max(1, (int) $this->created_at->startOfDay()->diffInDays($this->trial_ends_at->startOfDay()) + 1);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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
}
