<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    protected $primaryKey = 'area_id';
    public $timestamps = false;

    protected $fillable = [
        'area_name',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['area_name'] = strtoupper($value);
    }

    public function asociaciones()
    {
        return $this->hasMany(OlympiadAreaLevel::class, 'id_area');
    }
}
