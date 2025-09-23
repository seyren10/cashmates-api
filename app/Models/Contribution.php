<?php

namespace App\Models;

use App\Http\Traits\HasOrderScopes;
use App\Models\User;
use App\Models\Comment;
use App\Models\SavingsGoal;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contribution extends Model implements HasMedia
{

    use InteractsWithMedia, HasFactory, HasOrderScopes;

    protected $fillable = ["savings_goal_id", "user_id", "amount", "note"];

    protected function casts(): array
    {
        return [
            'amount' => 'float'
        ];
    }

    public function goal()
    {
        return $this->belongsTo(SavingsGoal::class, 'savings_goal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
