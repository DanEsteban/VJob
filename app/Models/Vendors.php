<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    use HasFactory;
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'name',
            'contact',
            'phone',
            'email',
            'billto_street',
            'billto_company',
            'billto_city',
            'billto_postal',
            'billto_state',
            'balance',
            'is_active'
        ];
}
