<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_LabelBar extends Model
{
    use HasFactory;
    protected $table = 'products__label_bars';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id', 
            'id_item',
            'code',
            'id_vendor'
        ];
}
