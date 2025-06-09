<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class School
 * 
 * @property int $id_school
 * @property string $school_name
 * @property int $id_province
 * 
 * @property Province $province
 * @property Collection|OlympicDetail[] $olympic_details
 *
 * @package App\Models
 */
class School extends Model
{
	protected $table = 'school';
	protected $primaryKey = 'id_school';
	public $timestamps = false;

	protected $casts = [
		'id_province' => 'int'
	];

	protected $fillable = [
		'school_name',
		'id_province'
	];

	public function province()
	{
		return $this->belongsTo(Province::class, 'id_province');
	}

	public function olympic_details()
	{
		return $this->hasMany(OlympicDetail::class, 'id_school');
	}
}
