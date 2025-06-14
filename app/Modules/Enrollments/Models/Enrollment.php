<?php

namespace App\Modules\Enrollments\Models;

use App\Modules\Persons\Models\OlympistDetail;
use App\Modules\Persons\Models\Person;
use App\Modules\Olympiads\Models\CategoryLevel;
use App\Modules\Enrollments\Models\EnrollmentList;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollment';
    protected $primaryKey = 'enrollment_id';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'olympist_detail_id',
        'academic_tutor_ci',
        'level_id',
        'list_id',
    ];
    public function olympistDetail()
    {
        return $this->belongsTo(OlympistDetail::class, 'olympist_detail_id', 'olympist_detail_id');
    }

    public function academicTutor()
    {
        return $this->belongsTo(Person::class, 'academic_tutor_ci', 'person_ci');
    }

    public function level()
    {
        return $this->belongsTo(CategoryLevel::class, 'level_id', 'level_id');
    }
    public function list()
    {
        return $this->belongsTo(EnrollmentList::class, 'list_id', 'list_id');
    }
}
