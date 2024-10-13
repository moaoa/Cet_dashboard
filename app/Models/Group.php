<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Quiz;
use App\Models\Homework;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;


class Group extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['lectures'];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'semester_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function teacher(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_groups');
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_groups')->withPivot('start_time', 'end_time');
    }
    public function homeworks(): BelongsToMany
    {
        return $this->BelongsToMany(Homework::class, 'homework_groups')->withPivot('due_time');
    }
    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }
}
