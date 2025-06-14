<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryLevel extends Model
{
    protected $table = 'category_level';
    protected $primaryKey = 'level_id';
    public $timestamps = false;

    protected $fillable = [
        'level_name',
    ];

    public function setLevelNameAttribute($value)
    {
        $this->attributes['level_name'] = strtoupper($value);
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'level_grades', 'level_id', 'grade_id')->withPivot('olympiad_id');
    }

    public function olympiadAreaLevel()//asociaciones
    {
        return $this->hasMany(OlympiadAreaLevel::class, 'level_id');
    }
    
    public function gradeLevelPivot()
    {
        return $this->hasMany(LevelGrade::class, 'level_id');
    }
    
}
