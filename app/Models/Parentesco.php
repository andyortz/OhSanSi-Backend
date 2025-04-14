<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parentesco extends Model
{
    protected $table = 'parentescos';
    public $timestamps = false;

    protected $fillable = [
        'id_olimpista',
        'id_tutor',
        'rol_parentesco',
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'id_tutor');
    }

    public function olimpista()
    {
        return $this->belongsTo(Olimpista::class, 'id_olimpista');
    }

    protected $primaryKey = null;
    public $incrementing = false;
}
