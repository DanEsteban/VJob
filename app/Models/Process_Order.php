<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process_Order extends Model
{
    use HasFactory;
    protected $table = 'process__orders';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_order',
            'id_invoice',
            'id_process',
            'status'
        ];
}
