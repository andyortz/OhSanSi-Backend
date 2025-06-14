<?php

namespace App\Modules\Persons\Models;

use App\Modules\Enrollments\Models\Enrollment;
use App\Modules\Enrollments\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    protected $primaryKey = 'person_ci';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'person_ci',
        'names',
        'surnames',
        'email',
        'birthdate',
        'phone',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_responsable_inscripcion', 'person_ci');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'academic_tutor_id', 'person_ci');
    }

    public function olympistDetail()
    {
        return $this->hasOne(OlympistDetail::class, 'olympist_ci', 'person_ci');
    }
}
