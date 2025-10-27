<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Cảm ơn bạn đã đăng ký nhận ưu đãi!')
            ->view('emails.subscriber-thankyou');
    }
}
