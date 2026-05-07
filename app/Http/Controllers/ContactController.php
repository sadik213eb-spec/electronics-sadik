<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Mail::to('sadik213.eb@gmail.com')->send(new ContactMail(
            senderName:    $request->name,
            senderEmail:   $request->email,
            mailSubject:       $request->subject,
            messageText:   $request->message,
        ));

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
