<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;
    protected $table = "logs";

    protected $fillable = [
        'id',
        'mensaje',
        'id_usuario',
        'deleted_at',
    ];

}
