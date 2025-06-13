<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grado';
    protected $primaryKey = 'id_grado';
    public $timestamps = false;

    protected $fillable = [
        'nombre_grado'
    ];
    public function niveles()
    {
        return $this->belongsToMany(NivelCategoria::class, 'grados_niveles', 'id_grado', 'id_nivel');
    }
    public function setNombreGradoAttribute($value)
    {
        $this->attributes['nombre_grado'] = strtoupper($value);
    }
    public function nivelGradoPivot()
{
    return $this->hasMany(NivelGrado::class, 'id_grado');
}
}
