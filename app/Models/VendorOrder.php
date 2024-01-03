<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOrder extends Model
{
    use HasFactory;
    protected $table = 'vendor_orders';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'date',
            'number',
            'vendor_id',
            'total',
            'status'
        ];
}
