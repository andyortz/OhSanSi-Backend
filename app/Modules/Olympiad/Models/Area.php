<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Area
 * 
 * @property int $id_area
 * @property string $name
 * 
 * @property Collection|Olympiad[] $olympiads
 *
 * @package App\Models
 */
class Area extends Model
{
	protected $table = 'area';
	protected $primaryKey = 'id_area';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function olympiads()
	{
		return $this->belongsToMany(Olympiad::class, 'area_level_olympiad', 'id_area', 'id_olympiad')
					->withPivot('id_level');
	}
	public function nivel_area()
    {
        return $this->hasMany(AreaLevelOlympiad::class, 'id_area');
    }
}
