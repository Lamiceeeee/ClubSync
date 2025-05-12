<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'end_date',
        'club_id',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'end_date' => 'datetime',
    ];

    /**
     * Get the user who created this vote.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the club this vote belongs to.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get all options for this vote.
     */
    public function options(): HasMany
    {
        return $this->hasMany(VoteOption::class);
    }

    /**
     * Get all participations through options.
     */
    public function participations(): HasManyThrough
    {
        return $this->hasManyThrough(
            VoteParticipation::class,
            VoteOption::class
        );
    }
}