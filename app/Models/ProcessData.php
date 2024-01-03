<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessData extends Model
{
    use HasFactory;
    protected $table = 'process_data';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'name',
            'id_customer',
            'id_responsible',
            'status',
            'percentage'
        ];
}
