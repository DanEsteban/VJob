<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
    use HasFactory;
    protected $table = 'unit_measures';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'abbreviation',
            'description'
        ];
}
