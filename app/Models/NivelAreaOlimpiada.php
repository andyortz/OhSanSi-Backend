<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelAreaOlimpiada extends Model
{
    protected $table = 'niveles_areas_olimpiadas';
    
    // Indica que no usamos un ID autoincremental
    public $incrementing = false;
    
    // Desactivar timestamps si no los usas
    public $timestamps = false;
    
    // Definir la clave primaria compuesta
    protected $primaryKey = ['id_olimpiada', 'id_area', 'id_nivel'];
    
    // Especificar que la clave es de tipo string (para serialización)
    protected $keyType = 'string';
    
    // Campos asignables masivamente
    protected $fillable = [
        'id_olimpiada',
        'id_area',
        'id_nivel'
    ];
    
    // Relaciones
    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }
    
    public function area()
    {
        return $this->belongsTo(AreaCompetencia::class, 'id_area', 'id_area');
    }
    
    public function nivel()
    {
        return $this->belongsTo(NivelCategoria::class, 'id_nivel', 'id_nivel');
    }
    
    public function getRouteKeyName()
    {
        return 'id_olimpiada';
    }    
    public function resolveRouteBinding($value, $field = null)
    {
        $parts = explode('-', $value);
        return $this->where([
            'id_olimpiada' => $parts[0],
            'id_area' => $parts[1],
            'id_nivel' => $parts[2],
        ])->firstOrFail();
    }
}
