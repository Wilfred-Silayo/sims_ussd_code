<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.student_forgot_password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:students,email' ],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $token = \Str::random(64);
        \DB::table('password_reset_tokens')->insert([
              'email'=>$request->email,
              'token'=>$token,
              'created_at'=>Carbon::now(),
        ]);
        
        $action_link = route('password.reset',['token'=>$token,'email'=>$request->email]);
        $body = "We have received a request to reset the password for <b>NIT SIMS </b> account associated with ".$request->email.". You can reset your password by clicking the link below";

       \Mail::send('email-forgot',['action_link'=>$action_link,'body'=>$body], function($message) use ($request){
             $message->from('info@nit.ac.tz','www.nit.sims.ac.tz');
             $message->to($request->email,'Your name')
                     ->subject('Reset Password');
       });

       return back()->with('success', 'We have e-mailed your password reset link! Please check your email');
    }
}
