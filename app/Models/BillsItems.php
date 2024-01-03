<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillsItems extends Model
{
    use HasFactory;
    protected $table = 'bills_items';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'id_bill', 'id_item', 'id_size', 'id_color', 'qty', 'unit', 'price'];
}
