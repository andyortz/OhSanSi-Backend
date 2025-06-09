<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympist\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Olympiad\Models\Olympiad;
use App\Modules\Olympiad\Models\Grade;
use App\Modules\Olympiad\Models\Enrollment;
use App\Modules\Olympist\Models\Person;


/**
 * Class OlympicDetail
 * 
 * @property int $id_olympic_detail
 * @property int $id_olympiad
 * @property int $ci_olympic
 * @property int $id_grade
 * @property int $id_school
 * @property int $ci_legal_guardian
 * 
 * @property Olympiad $olympiad
 * @property Person $person
 * @property Grade $grade
 * @property School $school
 * @property Collection|Enrollment[] $enrollments
 *
 * @package App\Models
 */
class OlympistDetail extends Model
{
	protected $table = 'olympist_detail';
	protected $primaryKey = 'id_olympist_detail';
	public $timestamps = false;

	protected $casts = [
		'id_olympiad' => 'int',
		'ci_olympic' => 'int',
		'id_grade' => 'int',
		'id_school' => 'int',
		'ci_legal_guardian' => 'int'
	];

	protected $fillable = [
		'id_olympiad',
		'ci_olympic',
		'id_grade',
		'id_school',
		'ci_legal_guardian'
	];

	public function olympiad()
	{
		return $this->belongsTo(Olympiad::class, 'id_olympiad');
	}

	public function person()
	{
		return $this->belongsTo(Person::class, 'ci_legal_guardian');
	}

	public function grade()
	{
		return $this->belongsTo(Grade::class, 'id_grade');
	}

	public function school()
	{
		return $this->belongsTo(School::class, 'id_school');
	}

	public function enrollments()
	{
		return $this->hasMany(Enrollment::class, 'id_olympist_detail');
	}

	public function olympist()
    {
        return $this->belongsTo(Person::class, 'ci_olympic', 'ci_person');
    }

}
