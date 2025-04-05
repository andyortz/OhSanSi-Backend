<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradoNivel extends Model
{
    protected $table = 'grados_niveles';
    public $timestamps = false;

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'id_grado',
        'id_nivel',
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelCategoria::class, 'id_nivel');
    }
}
