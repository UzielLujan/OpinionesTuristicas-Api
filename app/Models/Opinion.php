<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model; //Usa el modelo de MongoDB

class Opinion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'review',
        'polarity',
        'town',
        'region',
        'type',
        'usuario',
        'etiquetado_manual',
    ];
}