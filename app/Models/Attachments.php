<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    use HasFactory;
    protected $table = 'attachments';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'id_process',
            'id_phase',
            'id_stage',
            'type',
            'file_name',
            'file_location',
            'file_size',
        ];
}
