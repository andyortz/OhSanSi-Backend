<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Enrollments\Models\Enrollment;
use App\Modules\Persons\Models\OlympistDetail;

class Olympiad extends Model
{

    protected $table = 'olympiad';
    public $timestamps = false;
    protected $primaryKey = 'olympiad_id';

    protected $fillable = [
        'year',
        'cost',
        'start_date',
        'end_date',
        'created_in',
        'max_categories_per_olympist',
        'olympiad_name'
    ];

    public function areaLevels()
    {
        return $this->hasMany(OlympiadAreaLevel::class, 'olympiad_id', 'olympiad_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'olympiad_id', 'olympiad_id');
    }

    public function olympistDetails()
    {
        return $this->hasMany(OlympistDetail::class, 'olympiad_id', 'olympiad_id');
    }

    public function gradeLevels()
    {
        return $this->hasMany(LevelGrade::class, 'olympiad_id', 'olympiad_id');
    }
}
