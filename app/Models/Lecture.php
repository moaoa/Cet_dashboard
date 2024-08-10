<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lecture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time',
        'end_time',
        'day_of_week',
        'subject_id',
        'class_room_id',
        'group_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'day_of_week' => 'integer',
        'subject_id' => 'integer',
        'class_room_id' => 'integer',
        'group_id' => 'integer',
        'user_id' => 'integer',
    ];
    protected function start_time(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $value->format('H:i') : null,
        );
    }

    protected function end_time(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $value->format('H:i') : null,
        );
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
