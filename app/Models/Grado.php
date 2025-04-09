<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'id_grado';
    public $timestamps = false;

    protected $fillable = [
        'nombre_grado'
    ];

    public function setNombreGradoAttribute($value)
    {
        $this->attributes['nombre_grado'] = strtoupper($value);
    }
}
