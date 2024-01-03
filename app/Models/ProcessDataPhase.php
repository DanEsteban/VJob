<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessDataPhase extends Model
{
    use HasFactory;
    protected $table = 'process_data_phases';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_data',
            'name',
            'id_responsible',
            'status',
            'percentage'
        ];
}
