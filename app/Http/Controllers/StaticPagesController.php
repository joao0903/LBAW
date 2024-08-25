<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;

class StaticPagesController extends Controller
{
    function showAboutPage() {
        return view('pages.about');
    }

    function showFAQPage() {
        return view('pages.faq');
    }

    function showContactUsPage() {
        return view('pages.contactUs');
    }

    public function sendEmail(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $name = $request->name;
        $email = $request->email;
        $message = $request->message;

        $mailable = new MailNotify($name, $email, $message);

        Mail::to('feuptimes.tickets@gmail.com')->send($mailable);

        return redirect()->route('contactUs')->with('message', 'Thanks for your message. We\'ll be in touch.');
    }
}
