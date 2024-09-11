<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    public function groups(): BelongsToMany
    {
        //return $this->belongsToMany(Group::class, 'group_id');
        return $this->belongsToMany(Group::class);
    }
    public  function comments(): MorphMany
    {
        //return $this->hasMany(Comment::class);
        return $this->morphMany(Comment::class, 'commentable');
    }
    public  function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'user_subjects')->withPivot('passed');
    }
    public function isAdmin(){
        return $this->type == UserType::Student;
    }
}
