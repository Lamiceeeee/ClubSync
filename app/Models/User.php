<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

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
        'club_id',
        'email_notifications',
        'push_notifications',
        'notification_preferences'
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
     * The attributes that should be cast.
     *
     * @var array<string, string|array>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'notification_preferences' => 'array'
    ];

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
        return $this->hasMany(Vote::class, 'user_id');
    }

    /**
     * Get all vote participations for this user.
     */
    public function participations()
    {
        return $this->hasMany(VoteParticipation::class);
    }

    /**
     * Get all of the user's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
                   ->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Get the count of unread notifications (accessor).
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Check if user should receive email notifications.
     */
    public function routeNotificationForMail()
    {
        return $this->email_notifications ? $this->email : null;
    }

    /**
     * Check if user should receive push notifications.
     */
    public function routeNotificationForBroadcast()
    {
        return $this->push_notifications ? $this->id : null;
    }

    /**
     * Get the user's notification preferences for a specific type.
     */
    public function receivesNotification($type)
    {
        return in_array($type, $this->notification_preferences ?? []);
    }

    /**
     * Scope for users who should receive specific notification type.
     */
    public function scopeShouldReceiveNotification($query, $type)
    {
        return $query->whereJsonContains('notification_preferences', $type);
    }
}