<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsBalances extends Model
{
    use HasFactory;
    protected $table = 'product_balances';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id', 
            'id_item',
            'year',
            'month',
            'qty',
            'cost'
        ];
}
