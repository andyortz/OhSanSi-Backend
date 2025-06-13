<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    protected $table = 'colegio';

    protected $primaryKey = 'id_colegio';

    public $timestamps = false;

    protected $fillable = [
        'nombre_colegio',
        'id_provincia',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }

}
