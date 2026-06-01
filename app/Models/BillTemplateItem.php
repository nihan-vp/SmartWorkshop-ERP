<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillTemplateItem extends Model
{
    protected $fillable = ['bill_template_id', 'item_type', 'item_id', 'item_name', 'quantity', 'unit_price'];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(BillTemplate::class, 'bill_template_id');
    }
}
