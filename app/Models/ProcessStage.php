<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessStage extends Model
{
    use HasFactory;
    protected $table = 'process_stages';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'description',
            'id_phase',
            'ini_date',
            'end_date',
            'instructions',
            'id_phases',
            'has_responsible',
            'id_responsible',
            'has_requirements',
            'has_inventory_comparison',
            'has_send_mail'
        ];
}
