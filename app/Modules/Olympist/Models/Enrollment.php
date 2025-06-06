<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Enrollment
 * 
 * @property int $id_enrollment
 * @property int $id_olympic_detail
 * @property int|null $ci_academic_advisor
 * @property int $id_level
 * @property int $id_list
 * 
 * @property OlympicDetail $olympic_detail
 * @property Person|null $person
 * @property CategoryLevel $category_level
 * @property EnrollmentList $enrollment_list
 *
 * @package App\Models
 */
class Enrollment extends Model
{
	protected $table = 'enrollment';
	protected $primaryKey = 'id_enrollment';
	public $timestamps = false;

	protected $casts = [
		'id_olympic_detail' => 'int',
		'ci_academic_advisor' => 'int',
		'id_level' => 'int',
		'id_list' => 'int'
	];

	protected $fillable = [
		'id_olympic_detail',
		'ci_academic_advisor',
		'id_level',
		'id_list'
	];

	public function olympic_detail()
	{
		return $this->belongsTo(OlympicDetail::class, 'id_olympic_detail');
	}

	public function person()
	{
		return $this->belongsTo(Person::class, 'ci_academic_advisor');
	}

	public function category_level()
	{
		return $this->belongsTo(CategoryLevel::class, 'id_level');
	}

	public function enrollment_list()
	{
		return $this->belongsTo(EnrollmentList::class, 'id_list');
	}
}
