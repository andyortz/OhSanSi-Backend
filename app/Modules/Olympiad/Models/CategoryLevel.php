<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryLevel
 * 
 * @property int $id_level
 * @property string $name
 * 
 * @property GradeLevel|null $grade_level
 * @property Collection|Enrollment[] $enrollments
 * @property AreaLevelOlympiad|null $area_level_olympiad
 *
 * @package App\Models
 */
class CategoryLevel extends Model
{
	protected $table = 'category_level';
	protected $primaryKey = 'id_level';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function grade_level()
	{
		return $this->hasOne(GradeLevel::class, 'id_level');
	}

	public function enrollments()
	{
		return $this->hasMany(Enrollment::class, 'id_level');
	}

	public function area_level_olympiad()
	{
		return $this->hasOne(AreaLevelOlympiad::class, 'id_level');
	}
}
