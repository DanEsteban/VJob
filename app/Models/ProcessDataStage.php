<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessDataStage extends Model
{
    use HasFactory;
    protected $table = 'process_data_stages';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_phases',
            'name',
            'has_condition',
            'has_attachment_customer',
            'has_inventory_received',
            'has_responsible',
            'id_responsible',
            'has_date',
            'start_date',
            'end_date',
            'has_instructions',
            'instructions',
            'has_attachment',
            'has_comparison',
            'percentage',
            'has_send_mail',
            'status',
            'is_approved'
        ];
}
