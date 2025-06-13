<?php

namespace App\Modules\Enrollments\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Olympiads\Models\Olympiad;
use App\Modules\Persons\Models\Person;
use App\Modules\Enrollments\Models\Enrollment;

class EnrollmentList extends Model
{
    protected $table = 'enrollment_lists';
    protected $primaryKey = 'list_id';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'olympiad_id',
        'enrollment_responsible_ci',
        'list_creation_date',
    ];
    public function olympiad()
    {
        return $this->belongsTo(Olympiad::class, 'olympiad_id', 'olympiad_id');
    }
    public function responsible()
    {
        return $this->belongsTo(Person::class, 'enrollment_responsible_ci', 'person_ci');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'list_id', 'list_id');
    }
}
