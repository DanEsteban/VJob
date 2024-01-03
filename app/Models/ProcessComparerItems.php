<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessComparerItems extends Model
{
    use HasFactory;
    protected $table = 'process_comparer_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'id_stage',
        'id_product',
        'id_size',
        'id_color',
        'qty',
        'inventory',
        'balance'
    ];
}
