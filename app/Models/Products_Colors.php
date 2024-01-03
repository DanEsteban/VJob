<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_Colors extends Model
{
    use HasFactory;
    protected $table = 'products__colors';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id', 
            'id_item',
            'id_color'
        ];
}
