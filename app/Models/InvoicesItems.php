<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesItems extends Model
{
    use HasFactory;
    protected $table = 'invoices_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'id_invoice',
        'id_warehouse', 
        'id_item', 
        'id_size', 
        'id_color', 
        'qty', 
        'unit', 
        'price'
    ];
}
