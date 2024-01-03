<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpendituresItems extends Model
{
    use HasFactory;
    protected $table = 'expenditures_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'id_expenditure', 
        'id_item', 
        'id_size', 
        'id_color', 
        'qty', 
        'unit', 
        'cost'
    ];
}
