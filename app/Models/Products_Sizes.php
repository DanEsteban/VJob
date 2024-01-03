<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_Sizes extends Model
{
    use HasFactory;
    protected $table = 'products__sizes';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id', 
            'id_item',
            'id_size'
        ];
}
