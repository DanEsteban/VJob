<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoriesCustomers extends Model
{
    use HasFactory;
    protected $table = 'inventories_customers';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'type_transaction',
            'id_transaction',
            'id_customer',
            'id_product',
            'qty',
            'id_size',
            'id_color'
        ];
}
