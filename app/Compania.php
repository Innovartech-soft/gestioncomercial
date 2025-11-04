<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compania extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "companias";

    protected $fillable = [
        'id',
        'nombre',
        'aux_1'
    ];

    
}
