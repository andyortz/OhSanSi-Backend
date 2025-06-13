<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;

    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    protected $fillable = ['department_name'];


    public function setDepartmentNameAttribute($value)
    {
        $this->attributes['department_name'] = strtoupper($value);
    }

}

