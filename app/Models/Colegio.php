<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    protected $table = 'colegio';

    protected $primaryKey = 'id_colegio';

    public $timestamps = false;

    protected $fillable = [
        'nombre_colegio',
        'provincia',  
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia', 'id_provincia');
    }

}
