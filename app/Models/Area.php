<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    
    
    protected $table = 'areas_competencia';

      // Deshabilitar timestamps
    public $timestamps = false;

    protected $primaryKey = 'id_area'; 
    protected $fillable = [
        'id_olimpiada',
        'nombre',
        'imagen'
    ];
    
    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada');
    }

}