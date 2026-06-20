<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkshop;

class Product extends Model
{
    use BelongsToWorkshop;

    protected $fillable = ['name', 'barcode', 'category', 'price', 'cost_price', 'stock_qty', 'min_stock', 'unit', 'workshop_id'];

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function getStockQtyAttribute($value)
    {
        $branchId = auth()->check() ? auth()->user()->branch_id ?? session('active_branch_id') : null;
        if ($branchId) {
            $stock = $this->stocks()->where('branch_id', $branchId)->first();
            return $stock ? $stock->stock_qty : 0;
        }
        return $value; // Fallback to total/global
    }

    public function getMinStockAttribute($value)
    {
        $branchId = auth()->check() ? auth()->user()->branch_id ?? session('active_branch_id') : null;
        if ($branchId) {
            $stock = $this->stocks()->where('branch_id', $branchId)->first();
            return $stock ? $stock->min_stock : 0;
        }
        return $value;
    }

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
        $branchId = auth()->check() ? auth()->user()->branch_id ?? session('active_branch_id') : null;
        if ($branchId) {
            $stock = $this->stocks()->firstOrCreate(
                ['branch_id' => $branchId],
                ['stock_qty' => 0, 'min_stock' => $this->getAttributes()['min_stock'] ?? 0]
            );
            $stock->decrement('stock_qty', $quantity);
        }
        $this->decrement('stock_qty', $quantity);
    }

    public function addStock(int $quantity): void
    {
        $branchId = auth()->check() ? auth()->user()->branch_id ?? session('active_branch_id') : null;
        if ($branchId) {
            $stock = $this->stocks()->firstOrCreate(
                ['branch_id' => $branchId],
                ['stock_qty' => 0, 'min_stock' => $this->getAttributes()['min_stock'] ?? 0]
            );
            $stock->increment('stock_qty', $quantity);
        }
        $this->increment('stock_qty', $quantity);
    }
}
