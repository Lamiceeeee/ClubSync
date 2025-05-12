<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'club_id', // Add this for the club relationship
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ======================
    // NEW RELATIONSHIP METHODS
    // ======================

    /**
     * Get the club that the user belongs to.
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get all votes created by this user.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get all vote participations for this user.
     */
    public function participations()
    {
        return $this->hasMany(VoteParticipation::class);
    }
}