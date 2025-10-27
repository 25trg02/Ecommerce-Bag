<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Gửi mail xác nhận/cảm ơn
        Mail::to($email)->send(new \App\Mail\SubscriberThankYouMail());

        return back()->with('message', 'Đã gửi email xác nhận!');
    }
}
