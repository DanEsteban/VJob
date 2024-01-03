<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipToCustomer extends Model
{
    use HasFactory;
    protected $table = 'ship_to_customers';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_customer',
            'name',
            'address',
            'company',
            'city',
            'postal',
            'state'
        ];
}
