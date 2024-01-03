<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessCondition extends Model
{
    use HasFactory;
    protected $table = 'process_conditions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'id_stage',
        'question',
        'message_yes',
        'action_yes',
        'message_no',
        'action_no'
    ];
}
