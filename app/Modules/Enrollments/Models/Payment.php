<?php

namespace App\Modules\Enrollments\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'voucher',
        'payment_date',
        'total_amount',
        'verified',
        'verified_in',
        'verified_by',
        'list_id'
    ];
    
    public function responsible()
    {
        return $this->belongsTo(EnrollmentList::class, 'list_id', 'person_ci');
    }

    public function enrollmentList()
    {
        return $this->belongsTo(EnrollmentList::class, 'list_id', 'list_id');
    }
}