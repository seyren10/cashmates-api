<?php

namespace App\Models;

use App\Models\User;
use App\Models\SavingsGoal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
    ];

    protected static function booted()
    {
        /* automatically add join_code before creating a group */
        static::creating(function ($group) {
            $group->join_code = strtoupper(Str::random(8));
        });
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function savingsGoals()
    {
        return $this->hasMany(SavingsGoal::class);
    }
}
