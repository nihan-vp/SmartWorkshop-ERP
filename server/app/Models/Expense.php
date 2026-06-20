<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkshop;
use App\Traits\BelongsToBranch;

class Expense extends Model
{
    use BelongsToWorkshop, BelongsToBranch;

    protected $fillable = ['category', 'description', 'amount', 'payment_method', 'expense_date', 'workshop_id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];
}
