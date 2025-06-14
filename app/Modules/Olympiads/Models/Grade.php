<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grade';
    protected $primaryKey = 'grade_id';
    public $timestamps = false;

    protected $fillable = [
        'grade_name'
    ];
    public function levels()
    {
        return $this->belongsToMany(CategoryLevel::class, 'level_grades', 'grade_id', 'level_id');
    }
    public function setGradeNameAttribute($value)
    {
        $this->attributes['grade_name'] = strtoupper($value);
    }
    public function gradeLevelPivot()
{
    return $this->hasMany(LevelGrade::class, 'grade_id');
}
}
