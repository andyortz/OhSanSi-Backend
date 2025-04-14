<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $table = 'tutores';
    public $timestamps = false;

    protected $primaryKey = 'id_tutor';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'nombres',
        'apellidos',
        'ci',
        'celular',
        'correo_electronico',
    ];

    public function setNombresAttribute($value)
    {
        $this->attributes['nombres'] = strtoupper($value);
    }

    public function setApellidosAttribute($value)
    {
        $this->attributes['apellidos'] = strtoupper($value);
    }

    public function parentescos()
    {
        return $this->hasMany(Parentesco::class, 'id_tutor');
    }
}
