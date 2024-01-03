<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incomes extends Model
{
    use HasFactory;
    protected $table = 'incomes';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'number',
            'comments',
            'date',
            'total',
    ];
}
