<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserType;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ref_number',
        'type',
        'password',
        'email',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ref_number' => 'integer',
        // 'type' => UserType::class
        'type' => 'integer',
        'password' => 'hashed'
    ];

    public function groups(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public  function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public  function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'user_subjects')->withPivot('passed');
    }
}
