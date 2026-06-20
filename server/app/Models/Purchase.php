<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkshop;

class Purchase extends Model
{
    use BelongsToWorkshop;

    protected $fillable = [
        'workshop_id',
        'supplier_name',
        'invoice_number',
        'purchase_date',
        'total_amount',
        'payment_method',
        'payment_status',
        'items_description',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
    ];
}
