<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Olimpiada extends Model
{
    protected $table = 'olimpiadas';
    protected $primaryKey = 'id_olimpiada';
    public $timestamps = false;

    protected $fillable = [
        'gestion',
        'costo',
        'fecha_inicio',
        'fecha_fin',
        'creado_en',
        'max_categorias_olimpista',
        'nombre_olimpiada'
    ];

    public function nivelesAreas()
    {
        return $this->hasMany(NivelAreaOlimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function detalleOlimpistas()
    {
        return $this->hasMany(DetalleOlimpista::class, 'id_olimpiada', 'id_olimpiada');
    }
}
