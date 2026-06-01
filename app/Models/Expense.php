<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkshop;

class Expense extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['category', 'description', 'amount', 'payment_method', 'expense_date', 'workshop_id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];
}
