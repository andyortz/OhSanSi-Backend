<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Modules\Olympist\Models\OlympistDetail;
use App\Modules\Olympist\Models\EnrollmentList;
use App\Modules\Olympiad\Models\Enrollment;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Person
 * 
 * @property int $ci_person
 * @property string $names
 * @property string $surnames
 * @property string $email
 * @property Carbon|null $birthdate
 * @property string|null $phone
 * 
 * @property Collection|OlympicDetail[] $olympic_details
 * @property Collection|EnrollmentList[] $enrollment_lists
 * @property Collection|Enrollment[] $enrollments
 *
 * @package App\Models
 */
class Person extends Model
{
	protected $table = 'person';
	protected $primaryKey = 'ci_person';
	public $timestamps = false;

	protected $casts = [
		'birthdate' => 'datetime'
	];

	protected $fillable = [
		'names',
		'surnames',
		'email',
		'birthdate',
		'phone'
	];

	public function olympic_details()
	{
		return $this->hasMany(OlympicDetail::class, 'ci_legal_guardian');
	}

	public function enrollment_lists()
	{
		return $this->hasMany(EnrollmentList::class, 'ci_enrollment_responsible');
	}

	public function enrollments()
	{
		return $this->hasMany(Enrollment::class, 'ci_academic_advisor');
	}

	public function olympicDetail()
    {
        return $this->hasOne(OlympicDetail::class, 'ci_olympic', 'ci_person');
    }
}
