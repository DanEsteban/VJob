<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'number', 
        'tipo_documento', 
        'num_doc_sri', 
        'id_customer', 
        'date', 
        'phone', 
        'email', 
        'id_warehouse', 
        'subtotal', 
        'id_taxes', 
        'taxes', 
        'base0', 
        'base_iva', 
        'total', 
        'saldo', 
        'status', 
        'active', 
        'clave', 
        'autorizacion', 
        'fecha_autorizacion', 
        'doc_genera', 
        'estado_sri', 
        'created_at', 
        'updated_at'
    ];
}
