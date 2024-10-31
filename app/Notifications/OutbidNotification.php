<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class OutbidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $listing;
    protected $newBid;


    public function __construct($listing, $newBid)
    {
        $this->listing = $listing;
        $this->newBid = $newBid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        \Log::info('OutbidNotification triggered for user ID: ' . $notifiable->id);
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You have been outbid!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been outbid on the listing: ' . $this->listing->title . '.')
            ->line('The new highest bid is: $' . number_format($this->newBid->bid_amount, 2))
            ->action('View Listing', url('/listings/' . $this->listing->id))
            ->line('If you would like to place a higher bid, please visit the listing page.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'You have been outbid on the listing: ' . $this->listing->title,
            'listing_id' => $this->listing->id,
            'new_bid' => $this->newBid->bid_amount,
            'listing_title' => $this->listing->title,
            'outbid_by' => optional($this->newBid->user)->name
        ];
    }
}
