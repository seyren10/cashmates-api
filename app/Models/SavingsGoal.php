<?php

namespace App\Models;

use App\Models\Group;
use App\Models\Expense;
use App\Models\Contribution;
use App\Models\Scopes\OrderByDescScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([OrderByDescScope::class])]
class SavingsGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_amount',
        'deadline',
    ];

    protected $appends = ['current_balance'];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'target_amount' => 'float',
            'contributions_sum_amount' => 'float',
            'expenses_sum_amount' => 'float'
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /* Attributes */
    protected function currentBalance(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->contributions()->sum('amount') - $this->expenses()->sum('amount');
            }
        )->shouldCache();
    }
}
