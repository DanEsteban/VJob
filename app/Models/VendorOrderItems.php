<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOrderItems extends Model
{
    use HasFactory;
    protected $table = 'vendor_order_items';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'order_id',
            'item_id',
            'qty',
            'price',
            'receive',
            'balance'
        ];
}
