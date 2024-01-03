<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenditures extends Model
{
    use HasFactory;
    protected $table = 'expenditures';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'number',
            'comments',
            'date',
            'total',
    ];
}
