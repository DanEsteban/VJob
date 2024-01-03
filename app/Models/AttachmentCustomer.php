<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachmentCustomer extends Model
{
    use HasFactory;
    protected $table = 'attachment_customers';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'type_transaction',
            'id_transaction',
            'id_customer',
            'type',
            'file_name',
            'file_location',
            'file_size',
        ];
}
