<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class LevelGrade extends Model
{
    protected $table = 'level_grade';
    public $timestamps = false;

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'grade_id',
        'level_id',
        'olympiad_id',
    ];

    public function grade()
    {
        return $this->belongsTo(Grado::class, 'grade_id');
    }

    public function level()
    {
        return $this->belongsTo(CategoryLevel::class, 'level_id');
    }

    public function areaLevels()
    {
        // hasMany(Modelo, llave_foránea_en_la_OTRA_tabla)
        return $this->hasMany(NivelAreaOlimpiada::class, 'level_id', 'level_id');
    }
    
    public function olympiad()
    {
        return $this->belongsTo(Olimpiada::class, 'olympiad_id', 'olympiad_id');
    } 
}
