<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable implements FilamentUser
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'ref_number',
        'password',
        'email',
        'device_subscriptions',
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
        'password' => 'hashed'
    ];

    public function canAccessFilament(): bool
    {
        // Add logic here to determine if this admin can access Filament
        return true;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Add logic here to determine if this admin can access the given panel
        return true;
    }
}
