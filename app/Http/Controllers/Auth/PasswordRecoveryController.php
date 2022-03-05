<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class PasswordRecoveryController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Password Recovery Controller
    |--------------------------------------------------------------------------
    |
    | This controller is meant to contain functions
    | used in password recovery.
    */


    /**
     * Returns view for the password recovery
     * request form
     * 
     * @return View
     */
    public function showPasswordRecovery() {
        return view('auth.password-recovery');
    }


    /** 
     * Sends email with password recovery and
     * returns notice view
     * 
     * @return View
     */
    public function emailValidation(Request $request) {
        $request->validate(['email' => 'required|email']);
    
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? view('auth.password-recovery-notice')->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Returns the view with the password reset form
     * 
     * @return View
     */
    public function resetPasswordForm($token) {
        return view('auth.reset-password', ['token' => $token]);
    }


    /**
     * Changes the password for the one receive 
     * in the form and returns to login (or goes
     * back in error case)
     * 
     * @return View
     */
    public function updatePassword(Request $request) {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);
    

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->save();
    
                event(new PasswordReset($user));
            }
        );

    
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }



//     public function getNotice() {
//         return view('auth.verify-email');
//     }


//     public function resendEmail(Request $request) {
//         $request->user()->sendEmailVerificationNotification();
    
//         return back()->with('message', 'Verification link sent!');
//     }
}