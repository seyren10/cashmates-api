<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\SavingsGoal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Expense extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $fillable = ['savings_goal_id', 'user_id', 'amount', 'description'];

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
