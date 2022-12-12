<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'email',
        'birth_date',
        'address_cep',
        'address_place',
        'address_number',
        'address_neighborhood',
        'address_complement',
        'address_city',
    ];

    protected $casts = [
        'birth_date' => 'datetime:Y-m-d',
    ];

    public function sales () {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'id');
    }
}
