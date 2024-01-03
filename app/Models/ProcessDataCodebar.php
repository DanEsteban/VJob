<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessDataCodebar extends Model
{
    use HasFactory;
    protected $table = 'process_data_codebars';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'id_process',
        'id_stage',
        'id_invoice',
        'code',
        'image'
    ];
}
