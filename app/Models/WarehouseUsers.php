<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseUsers extends Model
{
    use HasFactory;
    protected $table = 'warehouse_users';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_user',
            'id_warehouse',
            'is_active'
        ];  
}