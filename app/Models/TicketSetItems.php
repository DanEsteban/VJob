<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSetItems extends Model
{
    protected $table = 'ticket_set_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'date', 
        'num_factura',
        'id_customer', 
        'id_item', 
        'qty',
        'status'
    ];
}
