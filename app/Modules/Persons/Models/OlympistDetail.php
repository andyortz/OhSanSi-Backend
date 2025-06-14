<?php

namespace App\Modules\Persons\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Olympiads\Models\Olympiad;
use App\Modules\Olympiads\Models\Grade;
use App\Modules\Enrollments\Models\Enrollment;
use App\Modules\Olympiads\Models\School;

class OlympistDetail extends Model
{
    protected $table = 'olympist_detail';
    protected $primaryKey = 'olympist_detail_id';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'olympiad_id',
        'olympist_ci',
        'grade_id',
        'school',
        'guardian_legal_ci',
    ];

    public function olympiad()
    {
        return $this->belongsTo(Olympiad::class, 'olympiad_id', 'olympiad_id');
    }

    public function olympist()
    {
        return $this->belongsTo(Person::class, 'olympist_ci', 'person_ci');
    }

    public function guardianLegal()
    {
        return $this->belongsTo(Person::class, 'guardian_legal_ci', 'person_ci');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'grade_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'olympist_detail_id', 'olympist_detail_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school', 'school_id');
    }
}
