<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageProduct extends Model
{
    use HasFactory;
    protected $table = 'image_products';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_product',
            'image_name',
            'image_folder'
        ];
}
