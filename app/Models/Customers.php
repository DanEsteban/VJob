<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id', 
            'company_name',
            'first_name',
            'midle_name',
            'last_name',
            'phone', 
            'work_phone',
            'email',
            'cc_email',
            'id_terms',
            'id_delivery',
            'billto_street',
            'billto_company',
            'billto_city',
            'billto_postal',
            'billto_state',
            'balance',
            'is_active'
        ];
}
