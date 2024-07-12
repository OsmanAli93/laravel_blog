<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "thumbnail",
        "title",
        "slug",
        "description",
        "message",
    ];

    public function likedBy (User $user)
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
}
