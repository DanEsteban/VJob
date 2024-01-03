<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessPhases extends Model
{
    use HasFactory;
    protected $table = 'process_phases';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'description',
            'id_process',
            'has_responsible',
            'id_responsible'
        ];
}
