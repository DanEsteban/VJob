<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessDataCondition extends Model
{
    use HasFactory;
    protected $table = 'process_data_conditions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'id_stage',
        'action_yes',
        'action_no'
    ];
}
