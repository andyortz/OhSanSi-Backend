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
    ];

    public function areas()
    {
        return $this->hasMany(Area::class, 'id_olimpiada');
    }
}
