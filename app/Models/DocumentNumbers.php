<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentNumbers extends Model
{
    use HasFactory;
    protected $table = 'document_numbers';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'type',
            'number'
        ];
}
