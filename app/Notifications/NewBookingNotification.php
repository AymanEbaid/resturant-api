<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;
    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }




    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'حجز جديد من: ' . $this->booking->name,
            'booking_id' => $this->booking->id,
            'reservation_date' => $this->booking->reservation_date,
            'reservation_time' => $this->booking->reservation_time,
            'number_of_guests' => $this->booking->number_of_guests,
            'status' => $this->booking->status,
            'contact_number' => $this->booking->contact_number

        ];
    }
}
