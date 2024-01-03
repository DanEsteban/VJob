<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizeMail extends Model
{
    use HasFactory;
    protected $table = 'customize_mails';
    protected $primaryKey = 'id';
    protected $fillable = [
            'id',
            'type',
            'subject',
            'message'
        ];
}
