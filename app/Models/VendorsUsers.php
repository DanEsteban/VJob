<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorsUsers extends Model
{
    use HasFactory;
    protected $table = 'vendors_users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'id_vendor',
        'user',
        'password'
    ];
}
