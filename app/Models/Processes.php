<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processes extends Model
{
    use HasFactory;
    protected $table = 'processes';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'description',
            'has_responsible',
            'id_responsible',
            'inventory_received'
        ];
}
