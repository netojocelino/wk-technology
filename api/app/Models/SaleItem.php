<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'unit_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function sale () {
        return $this->belongsTo(SalesOrder::class, 'sale_id', 'id');
    }

    public function product () {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}
