<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    use HasFactory;
    protected $table = 'warehouses';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'wh_name', 'is_active'];
}
