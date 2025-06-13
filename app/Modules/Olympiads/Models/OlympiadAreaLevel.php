<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;


class OlympiadAreaLevel extends Model
{
    protected $table = 'olympiad_area_levels';
    public $timestamps = false;

    protected $fillable = [
        'level_id',
        'area_id',
        'olympiad_id',
        //'max_niveles',
    ];

    public $incrementing = false;
    protected $primaryKey = null;
    public function olympiad()
    {
        return $this->belongsTo(Olympiad::class, 'olympiad_id', 'olympiad_id');
    } 
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function gradeLevel()
    {
        return $this->belongsTo(LevelGrade::class, 'level_id', 'level_id');
    }

    public function level()
    {
        return $this->belongsTo(CategoryLevel::class, 'level_id');
    }
    
    public function getRouteKeyName()
    {
        return 'olympiad_id';
    }    
    public function resolveRouteBinding($value, $field = null)
    {
        $parts = explode('-', $value);
        return $this->where([
            'olympiad_id' => $parts[0],
            'area_id' => $parts[1],
            'level_id' => $parts[2],
        ])->firstOrFail();
    }
}
