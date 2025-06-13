<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Olimpiada extends Model
{

    protected $table = 'olimpiada';
    public $timestamps = false;
    protected $primaryKey = 'id_olimpiada';

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

    public function nivelesGrados()
    {
        return $this->hasMany(NivelGrado::class, 'id_olimpiada', 'id_olimpiada');
    }
}
