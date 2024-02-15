<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesItems extends Model
{
    use HasFactory;
    protected $table = 'documents_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'id_income', 
        'id_item', 
        'id_size', 
        'id_color', 
        'qty', 
        'unit', 
        'cost', 
        'created_at', 
        'updated_at'
    ];
}
