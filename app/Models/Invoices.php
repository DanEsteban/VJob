<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'number',
            'id_customer',
            'date',
            'phone',
            'email',
            'id_term',
            'billto',
            'id_shipto',
            'id_warehouse',
            'porcentage',
            'id_taxes',
            'taxes',
            'total',
            'status',
            'active'
    ];
}
