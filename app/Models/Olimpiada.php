<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Olimpiada extends Model
{
    //
    protected $table = 'olimpiadas';
  // Deshabilitar timestamps
    public $timestamps = false;
    
    protected $fillable = [
        'gestion',
        'costo',
        'fecha_inicio',
        'fecha_fin',
        'creado_en' // Nota: Corregí el nombre basado en tu esquema (de "creadc_en" a "creado_en")
    ];
    public function areas()
    {
        return $this->hasMany(Area::class, 'id_olimpiada');
    }
}
