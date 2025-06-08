<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AreaLevelOlympiad
 * 
 * @property int $id_olympiad
 * @property int $id_area
 * @property int $id_level
 * 
 * @property Olympiad $olympiad
 * @property Area $area
 * @property CategoryLevel $category_level
 *
 * @package App\Models
 */
class AreaLevelOlympiad extends Model
{
	protected $table = 'area_level_olympiad';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_olympiad' => 'int',
		'id_area' => 'int',
		'id_level' => 'int'
	];

	protected $fillable = [
		'id_olympiad',
		'id_area',
		'id_level'
	];

	public function olympiad()
	{
		return $this->belongsTo(Olympiad::class, 'id_olympiad');
	}

	public function area()
	{
		return $this->belongsTo(Area::class, 'id_area');
	}

	public function category_level()
	{
		return $this->belongsTo(CategoryLevel::class, 'id_level');
	}
	public function grade_level()
    {
        return $this->belongsTo(GradeLevel::class, 'id_nivel', 'id_nivel');
    }
}
