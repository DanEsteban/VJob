<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentsDetails extends Model
{
    use HasFactory;
    protected $table = 'payments_details';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_payment',
            'invoice',
            'amount'
        ];
}
