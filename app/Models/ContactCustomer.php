<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactCustomer extends Model
{
    use HasFactory;
    protected $table = 'contact_customers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'id_customer',
        'name',
        'email',
        'phone'
    ];
}
