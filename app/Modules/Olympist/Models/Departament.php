<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Departament
 * 
 * @property int $id_departament
 * @property string $departament_name
 * 
 * @property Collection|Province[] $provinces
 *
 * @package App\Models
 */
class Departament extends Model
{
	protected $table = 'departament';
	protected $primaryKey = 'id_departament';
	public $timestamps = false;

	protected $fillable = [
		'departament_name'
	];

	public function provinces()
	{
		return $this->hasMany(Province::class, 'id_departament');
	}
}
