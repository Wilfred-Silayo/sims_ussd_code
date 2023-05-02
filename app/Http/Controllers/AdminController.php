<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Admin;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Middleware\NoCacheMiddleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    //Disable caches using no cache middleware
    public function __construct()
    {
        $this->middleware(NoCacheMiddleware::class);
    }

    public function index(){
        return view('admin.login_form');
    }

    public function dashboard(){
        $dateOfToday=Carbon::now()->format('F d, Y');
        $studentsCount = DB::table('students')->count();
        $lecturersCount = DB::table('lecturers')->count();
        $programsCount = DB::table('programs')->count();
        $modulesCount = DB::table('modules')->count();
        $departmentsCount = DB::table('departments')->count();
        $published = DB::table('enrollment')->where('published', true)->exists();
        $currentAcademicYear = AcademicYear::where('current', true)->first();

        return view('admin.dashboard',['dateOfToday'=> $dateOfToday,
        'studentsCount'=>$studentsCount,'lecturersCount'=>$lecturersCount,
        'programsCount'=>$programsCount,'modulesCount'=>$modulesCount,'departmentsCount'=>$departmentsCount,
        'published'=> $published,'academicYear'=>$currentAcademicYear,
        ]);

    }

    public function login(Request $request){
        $validatedData=  $request->validate([
            'username'=>['required','string'],
            'password'=>['required','string'],
        ]);

        $check= $request->only('username','password');
        if(Auth::guard('admin')->attempt(['username'=>$check['username'],'password'=>$check['password']])){
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        
            return back()->with('error','These credentials do not match our records.');
        
    }
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route('admin.login.form');
    }


    public function create(): View
    {
        return view('auth.admin_forgot_password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:admins,email' ],
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
        
        $action_link = route('admin.password.reset',['token'=>$token,'email'=>$request->email]);
        $body = "We have received a request to reset the password for <b>NIT SIMS </b> account associated with ".$request->email.". You can reset your password by clicking the link below";

       \Mail::send('email-forgot',['action_link'=>$action_link,'body'=>$body], function($message) use ($request){
             $message->from('info@nit.ac.tz','www.nit.sims.ac.tz');
             $message->to($request->email,'Your name')
                     ->subject('Reset Password');
       });

       return back()->with('success', 'We have e-mailed your password reset link! Please check your email');
    }

    /**
     * Display the password reset view.
     */
    public function createReset(Request $request, $token = null): View
    {
        return view('auth.admin_reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeReset(Request $request): RedirectResponse
    {
       
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $request->validate([
            'email'=>'required|email|exists:admins,email',
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

            Admin::where('email', $request->email)->update([
                'password'=>Hash::make($request->password)
            ]);

            \DB::table('password_reset_tokens')->where([
                'email'=>$request->email
            ])->delete();

            return redirect()->route('admin.login.form')->with('info', 'Your password has been changed! You can login with new password')->with('verifiedEmail', $request->email);
        }
    }

    public function changePassword(){
        return view('admin.password');
    } 

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate( [
            'current_password' => ['required', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        $user = Auth::guard('admin')->user();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error','The current password is incorrect.');
        }
     
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return back()->with('status', 'password-updated');
        
        
    }

   //profile
   public function fullProfile(){
    $user = Auth::guard('admin')->user();
    return view('admin.profile')->with('user',$user);
   } 
   public function profileUpdate(){
    $user = Auth::guard('admin')->user();
    return view('admin.edit_profile')->with('user',$user);
   }

    public function storeProfile(Request $request)
    {
    $request->validate([
        'profile_photo' => ['required', 'image', 'max:2048'], // Max file size is 2MB
    ]);

    $user = Auth::guard('admin')->user();
    $file = $request->file('profile_photo');
    $extension = $file->getClientOriginalExtension();
    $filename = $user->username . '_' . time() . '.' . $extension;
    $path = $file->storeAs('public', $filename);
    
    // Check if the new photo is not equal to the default photo "user.png"
    if ($user->profile_photo_path !== 'user.png') {
        // Delete the old photo
        Storage::delete('public/' . $user->profile_photo_path);
    }
    
    $user->profile_photo_path = $filename;
    $user->save();
    return back()->with('info', 'Profile Photo Updated Successfully');
    }

    //admin information
    public function updateAdmin(Request $request, $username){
        $username = str_replace('-','/',$username);
        $request->validate([
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:admins,email,'.$username.',username'],
            'firstname' => ['required', 'string'],
            'middlename' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'gender' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:12'],
            'nationality' => ['required', 'string', 'max:255'],
            'maritalstatus' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
        ]);
        
        $admin = Admin::where('username', $username)->firstOrFail();
    
        $admin->update([
            'email' => $request->filled('email') ? $request->email : $admin->email,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'nationality' => $request->nationality,
            'maritalstatus' => $request->maritalstatus,
            'dob' => $request->dob,
        ]);
        
        return back()->with('info','Admin updated successfully');
    }
    
    
   
}