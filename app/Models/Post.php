<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "thumbnail",
        "title",
        "slug",
        "description",
        "message",
    ];

    public function likedByUser (User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes () : HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments () : HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}
