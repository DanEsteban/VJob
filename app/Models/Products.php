<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id', 
            'id_type',
            'id_group',
            'item_name',
            'part_number',
            'id_unit_measure', 
            'purchase_description',
            'sales_description',
            'cost',
            'price',
            'max_order',
            'min_order',
            'notes',
            'id_process',
            'is_active'
        ];
}
