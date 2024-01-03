<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotesCustomer extends Model
{
    use HasFactory;
    protected $table = 'notes_customers';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_customer',
            'note',
        ];
}
