<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    //
    protected $table = 'tutores';
    public $timestamps = false;

    protected $primaryKey = 'id_tutor';
    protected $fillable =[
        'nombres',
        'apellidos',
        'ci',
        'celular',
        'correo_electronico',
        'rol_parentesco'
    ];

    public function olimpistas()
    {
        return $this->hasMany(Olimpista::class, 'id_tutor');
    }
}
