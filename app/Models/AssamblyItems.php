<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssamblyItems extends Model
{
    use HasFactory;
    protected $table = 'assambly_items';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_item_main',
            'id_item',
            'qty'
        ];
}
