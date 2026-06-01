<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToWorkshop;

class BillTemplate extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['name', 'description', 'discount', 'tax', 'workshop_id'];

    protected $casts = [
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BillTemplateItem::class);
    }
}
