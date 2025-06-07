<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Olympiad
 * 
 * @property int $id_olympiad
 * @property int $year
 * @property float $cost
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $created_at
 * @property int $max_olympic_categories
 * @property string $olympiad_name
 * 
 * @property GradeLevel|null $grade_level
 * @property Collection|OlympicDetail[] $olympic_details
 * @property Collection|EnrollmentList[] $enrollment_lists
 * @property Collection|Area[] $areas
 *
 * @package App\Models
 */
class Olympiad extends Model
{
	protected $table = 'olympiad';
	protected $primaryKey = 'id_olympiad';
	public $timestamps = false;

	protected $casts = [
		'year' => 'int',
		'cost' => 'float',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'max_olympic_categories' => 'int'
	];

	protected $fillable = [
		'year',
		'cost',
		'start_date',
		'end_date',
		'max_olympic_categories',
		'olympiad_name'
	];

	public function grade_level()
	{
		return $this->hasMany(GradeLevel::class, 'id_olympiad');
	}

	public function olympic_details()
	{
		return $this->hasMany(OlympicDetail::class, 'id_olympiad');
	}

	public function enrollment_lists()
	{
		return $this->hasMany(EnrollmentList::class, 'id_olympiad');
	}

	public function areas()
	{
		return $this->belongsToMany(Area::class, 'area_level_olympiad', 'id_olympiad', 'id_area')
					->withPivot('id_level');
	}
}
