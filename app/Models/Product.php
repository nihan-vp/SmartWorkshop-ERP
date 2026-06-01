<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkshop;

class Product extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['name', 'sku', 'category', 'price', 'cost_price', 'stock_qty', 'min_stock', 'unit', 'workshop_id'];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    public function isLowStock(): bool
    {
        return $this->stock_qty <= $this->min_stock;
    }

    public function deductStock(int $quantity): void
    {
        $this->decrement('stock_qty', $quantity);
    }

    public function addStock(int $quantity): void
    {
        $this->increment('stock_qty', $quantity);
    }
}
