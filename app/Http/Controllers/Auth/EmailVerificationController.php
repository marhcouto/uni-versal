<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Closure;

class EmailVerificationController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is meant to contain functions
    | used in the account verification upon registry.
    */


    public function handle(EmailVerificationRequest $request) {
        $request->fulfill();
    
        return redirect('/');
    }

    public function getNotice() {
        return view('auth.verify-email');
    }


    public function resendEmail(Request $request) {
        $request->user()->sendEmailVerificationNotification();
    
        return back()->with('message', 'Verification link sent!');
    }
}