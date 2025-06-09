<?php

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Olympiad\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $id_payment
 * @property string $receipt
 * @property Carbon $payment_date
 * @property int $id_list
 * @property float $total_amount
 * @property bool $verified
 * @property Carbon|null $verified_at
 * @property string|null $verified_by
 * 
 * @property EnrollmentList $enrollment_list
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payment';
	protected $primaryKey = 'id_payment';
	public $timestamps = false;

	protected $casts = [
		'payment_date' => 'datetime',
		'id_list' => 'int',
		'total_amount' => 'float',
		'verified' => 'bool',
		'verified_at' => 'datetime'
	];

	protected $fillable = [
		'receipt',
		'payment_date',
		'id_list',
		'total_amount',
		'verified',
		'verified_at',
		'verified_by'
	];

	public function enrollment_list()
	{
		return $this->belongsTo(EnrollmentList::class, 'id_list');
	}
}
