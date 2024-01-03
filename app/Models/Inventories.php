<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventories extends Model
{
    use HasFactory;
    protected $table = 'inventories';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'type',
            'id_transaction',
            'id_warehouse',
            'id_item',
            'id_size',
            'id_color',
            'num_transaction_one',
            'num_transaction_two',
            'cost',
            'price',
            'qty'
        ];
}
