<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelGrado extends Model
{
    protected $table = 'niveles_grados';
    protected $primaryKey = 'id_nivel_grado';
    public $timestamps = false;

    protected $fillable = [
        'id_nivel',
        'id_grado',
    ];

    public function nivel()
    {
        return $this->belongsTo(NivelCategoria::class, 'id_nivel');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }
}
