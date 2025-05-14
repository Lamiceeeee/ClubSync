<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Vote;

class NewVoteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The vote instance
     * @var Vote
     */
    public $vote;

    /**
     * Create a new notification instance
     * @param Vote $vote
     */
    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
    }

    /**
     * Get the notification's delivery channels
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['database'];
        
        if ($notifiable->email_notifications) {
            $channels[] = 'mail';
        }
        
        if ($notifiable->push_notifications) {
            $channels[] = 'broadcast';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("[ClubSync] New Vote: {$this->vote->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new vote has been created in {$this->vote->club->name}:")
            ->line("**{$this->vote->title}**")
            ->line($this->vote->description ?? 'No description provided')
            ->action('View Vote', route('votes.show', $this->vote))
            ->line("Voting ends: {$this->vote->end_date->format('l, F jS \a\t g:i A')}")
            ->line('Thank you for using ClubSync!');
    }

    /**
     * Get the array representation for database storage
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'vote_id' => $this->vote->id,
            'title' => $this->vote->title,
            'club_id' => $this->vote->club_id,
            'club_name' => $this->vote->club->name,
            'creator_name' => $this->vote->creator->name,
            'end_date' => $this->vote->end_date->toDateTimeString(),
            'message' => "New vote: {$this->vote->title}",
            'action_url' => route('votes.show', $this->vote),
            'type' => 'vote_created'
        ];
    }

    /**
     * Get the broadcastable representation of the notification
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => "New Vote: {$this->vote->title}",
            'message' => "A new vote has been created in {$this->vote->club->name}",
            'action_url' => route('votes.show', $this->vote),
            'created_at' => now()->toDateTimeString()
        ]);
    }
}