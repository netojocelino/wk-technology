<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_date',
        'customer_id',
    ];

    protected $casts = [
        'sale_date' => 'datetime:Y-m-d',
    ];

    public function customer () {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function items () {
        return $this->hasMany(SaleItem::class, 'sale_id', 'id');
    }

}
