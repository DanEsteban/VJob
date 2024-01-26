<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_Warehouses extends Model
{
    use HasFactory;
    protected $table = 'products__warehouses';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id', 
            'id_item',
            'id_warehouse',
            'qty_balance'
        ];
}
