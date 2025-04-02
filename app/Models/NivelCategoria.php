<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NivelCategoria extends Model
{
    protected $table = 'niveles_categoria'; 

    protected $primaryKey = 'id_nivel'; 

    public $timestamps = false; 

    protected $fillable = [
        'nombre',
        'id_area',
        'permite_seleccion_nivel',
    ];

    public function setnombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }
    
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    public function grados()
    {
        return $this->belongsToMany(Grado::class, 'niveles_grados', 'id_nivel', 'id_grado');
    }
}
