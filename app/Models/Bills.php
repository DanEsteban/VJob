<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'number', 'id_vendor', 'date', 'phone', 'email', 'id_term', 'billto', 'total', 'status', 'active'];
}
