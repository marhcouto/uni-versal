<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Question;

class ContactsController extends Controller
{
    /**
     * Shows the home page
     *
     * @return View
     */
    public function show(){
      return view('pages.contacts');
    }

    /**
     * Sends an email from the contact form
     * in the contacts page
     * 
     * @return View
     */
    public function sendMail(Request $request) {

      $details = [
        'senderEmail' => $request->input('contact-email'),
        'senderName' => $request->input('contact-name'),
        'subject' => $request->input('contact-subject'),
        'content' => $request->input('contact-message')
      ];

      Mail::to('lbaw2106@outlook.com')->send(new ContactMail($details));
      return back();
  }
}