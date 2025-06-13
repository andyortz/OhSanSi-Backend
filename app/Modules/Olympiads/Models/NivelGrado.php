<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class NivelGrado extends Model
{
    protected $table = 'grado_nivel';
    public $timestamps = false;

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'id_grado',
        'id_nivel',
        'id_olimpiada',
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelCategoria::class, 'id_nivel');
    }

    public function nivelAreas()
    {
        // hasMany(Modelo, llave_foránea_en_la_OTRA_tabla)
        return $this->hasMany(NivelAreaOlimpiada::class, 'id_nivel', 'id_nivel');
    }
    
    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    } 
}
