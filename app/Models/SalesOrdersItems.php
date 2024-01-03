<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrdersItems extends Model
{
    use HasFactory;
    protected $table = 'sales_orders_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'id_order', 
        'id_item', 
        'id_size', 
        'id_color', 
        'qty', 
        'unit', 
        'price'
    ];
}
