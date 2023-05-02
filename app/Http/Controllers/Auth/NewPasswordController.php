<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Student;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request, $token = null): View
    {
        return view('auth.student_reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
       
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $request->validate([
            'email'=>'required|email|exists:students,email',
            'password'=>'required|min:8|confirmed',
            'password_confirmation'=>'required',
        ]);

        $check_token = \DB::table('password_reset_tokens')->where([
            'email'=>$request->email,
            'token'=>$request->token,
        ])->first();

        if(!$check_token){
            return back()->withInput()->with('fail', 'Invalid token');
        }else{

            Student::where('email', $request->email)->update([
                'password'=>Hash::make($request->password)
            ]);

            \DB::table('password_reset_tokens')->where([
                'email'=>$request->email
            ])->delete();

            return redirect()->route('student.login')->with('info', 'Your password has been changed! You can login with new password')->with('verifiedEmail', $request->email);
        }
    }
}
