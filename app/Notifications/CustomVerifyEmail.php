<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public $otpCode;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($otpCode = null)
    {
        $this->otpCode = $otpCode;
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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('تفعيل بريدك الإلكتروني - ' . config('app.name'))
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('شكراً لتسجيلك في ' . config('app.name'))
            ->line('لتفعيل بريدك الإلكتروني، يمكنك استخدام أحد الطريقتين:')
            ->line('')
            ->line('**الطريقة الأولى: استخدام كود التفعيل**')
            ->line('الكود الخاص بك:')
            ->line('## **' . $this->otpCode . '**')
            ->line('صالح لمدة 10 دقائق.')
            ->line('')
            ->line('**الطريقة الثانية: الضغط على الزر**')
            ->action('تفعيل البريد مباشرة', $verificationUrl)
            ->line('')
            ->line('إذا لم تقم بإنشاء حساب، تجاهل هذا البريد.')
            ->salutation('تحياتنا، فريق ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
