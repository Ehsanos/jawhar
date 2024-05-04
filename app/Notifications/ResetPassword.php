<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

        public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('أنت تتلقى هذا البريد الإلكتروني لأننا تلقينا طلب إعادة تعيين كلمة المرور لحسابك.')
            ->line('لأعادة تعيين كلمة المرور اضغط على الزر ادناه')
            ->action('تغيير كلمة المرور', url(app()->getLocale().'/password/reset', $this->token))
            ->line('إذا لم تطلب إعادة تعيين كلمة المرور ، فلا داعي لاتخاذ أي إجراء آخر.');
    }

}
