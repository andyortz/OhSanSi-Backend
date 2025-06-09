<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Province
 * 
 * @property int $id_province
 * @property string $province_name
 * @property int $id_departament
 * 
 * @property Departament $departament
 * @property Collection|School[] $schools
 *
 * @package App\Models
 */
class Province extends Model
{
	protected $table = 'province';
	protected $primaryKey = 'id_province';
	public $timestamps = false;

	protected $casts = [
		'id_departament' => 'int'
	];

	protected $fillable = [
		'province_name',
		'id_departament'
	];

	public function departament()
	{
		return $this->belongsTo(Departament::class, 'id_departament');
	}

	public function schools()
	{
		return $this->hasMany(School::class, 'id_province');
	}
}
