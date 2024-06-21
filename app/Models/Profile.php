<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'avatar',
        'username',
        'about',
        'background_image',
        'country',
        'address',
        'city',
        'state',
        'postal_code'
    ];

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
