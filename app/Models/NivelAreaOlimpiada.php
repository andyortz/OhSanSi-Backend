<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelAreaOlimpiada extends Model
{
    protected $table = 'niveles_areas_olimpiadas';
    public $timestamps = false;

    protected $fillable = [
        'id_nivel',
        'id_area',
        'id_olimpiada',
        'max_niveles',
    ];

    public $incrementing = false;
    protected $primaryKey = null;
    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }
    
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }
    
    public function nivelGrado()
    {
        return $this->belongsTo(NivelGrado::class, 'id_nivel', 'id_nivel');
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
