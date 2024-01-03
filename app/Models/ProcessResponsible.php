<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessResponsible extends Model
{
    use HasFactory;
    protected $table = 'process_responsibles';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_user',
            'type',
            'id_process',
            'id_phase',
            'id_stage',
            'rating',
            'status'
        ];
}
