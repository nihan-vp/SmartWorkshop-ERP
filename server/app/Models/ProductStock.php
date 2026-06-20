<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $fillable = ['product_id', 'branch_id', 'stock_qty', 'min_stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_qty <= $this->min_stock;
    }
}
