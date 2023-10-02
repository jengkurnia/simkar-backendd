<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'gender',
        'age',
        'address',
        'education',
        'phone',
        'date_entry',
        'year_service',
        'position',
        'photo',

        'team_id',
        'violation_id',
        'is_verfied',
        'verified_at'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }
}
