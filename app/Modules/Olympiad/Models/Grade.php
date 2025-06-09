<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Grade
 * 
 * @property int $id_grade
 * @property string $grade_name
 * 
 * @property GradeLevel|null $grade_level
 * @property Collection|OlympicDetail[] $olympic_details
 *
 * @package App\Models
 */
class Grade extends Model
{
	protected $table = 'grade';
	protected $primaryKey = 'id_grade';
	public $timestamps = false;

	protected $fillable = [
		'grade_name'
	];

	public function grade_level()
	{
		return $this->hasOne(GradeLevel::class, 'id_grade');
	}

	public function olympic_details()
	{
		return $this->hasMany(OlympicDetail::class, 'id_grade');
	}
}
