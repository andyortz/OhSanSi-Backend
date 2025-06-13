<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    protected $table = 'provinces';
    protected $primaryKey = 'province_id';

    protected $fillable = [
        'province_name',
        'department_id',
    ];


    public function setProvinceNameAttribute($value)
    {
        $this->attributes['province_name'] = strtoupper($value);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function school()
    {
        return $this->hasMany(School::class, 'province_id');
    }
}
