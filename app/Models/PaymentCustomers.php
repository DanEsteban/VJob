<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCustomers extends Model
{
    use HasFactory;
    protected $table = 'payment_customers';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_customer',
            'date',
            'id_term',
            'reference',
            'card_number',
            'exp_date',
            'memo'
        ];
}
