<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GradeLevel
 * 
 * @property int $id_level
 * @property int $id_grade
 * @property int|null $id_olympiad
 * 
 * @property CategoryLevel $category_level
 * @property Grade $grade
 * @property Olympiad|null $olympiad
 *
 * @package App\Models
 */
class GradeLevel extends Model
{
	protected $table = 'grade_level';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_level' => 'int',
		'id_grade' => 'int',
		'id_olympiad' => 'int'
	];

	protected $fillable = [
		'id_level',
		'id_grade',
		'id_olympiad'
	];

	public function category_level()
	{
		return $this->belongsTo(CategoryLevel::class, 'id_level');
	}

	public function grade()
	{
		return $this->belongsTo(Grade::class, 'id_grade');
	}

	public function olympiad()
	{
		return $this->belongsTo(Olympiad::class, 'id_olympiad');
	}
}
