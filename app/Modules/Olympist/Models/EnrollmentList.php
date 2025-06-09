<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympist\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EnrollmentList
 * 
 * @property int $id_list
 * @property int $id_olympiad
 * @property string $status
 * @property int $ci_enrollment_responsible
 * @property Carbon $list_creation_date
 * 
 * @property Olympiad $olympiad
 * @property Person $person
 * @property Collection|Enrollment[] $enrollments
 * @property Collection|Payment[] $payments
 *
 * @package App\Models
 */
class EnrollmentList extends Model
{
	protected $table = 'enrollment_list';
	protected $primaryKey = 'id_list';
	public $timestamps = false;

	protected $casts = [
		'id_olympiad' => 'int',
		'ci_enrollment_responsible' => 'int',
		'list_creation_date' => 'datetime'
	];

	protected $fillable = [
		'id_olympiad',
		'status',
		'ci_enrollment_responsible',
		'list_creation_date'
	];

	public function olympiad()
	{
		return $this->belongsTo(Olympiad::class, 'id_olympiad');
	}

	public function person()
	{
		return $this->belongsTo(Person::class, 'ci_enrollment_responsible');
	}

	public function enrollments()
	{
		return $this->hasMany(Enrollment::class, 'id_list');
	}

	public function payments()
	{
		return $this->hasMany(Payment::class, 'id_list');
	}
}
