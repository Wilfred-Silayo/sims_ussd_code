<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Lecturer;
use App\Models\Department;
use App\Http\Middleware\NoCacheMiddleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

use Auth;

class LecturerController extends Controller
{
     //Disable caches using no cache middleware
     public function __construct()
     {
         $this->middleware(NoCacheMiddleware::class);
     }
     
     //lecturer login page
    public function index(){
        return view('lecturer.login_form');
    }

    public function dashboard(){
        $dateOfToday=Carbon::now()->format('F d, Y');
        $currentDate=Carbon::now();
        if($currentDate->month>=11 && $currentDate->day>=1){
        $academicYear=$currentDate->year.'/'.($currentDate->year+1);
        return view('lecturer.dashboard',['academicYear'=>$academicYear,'dateOfToday'=>$dateOfToday]);
        }
        $academicYear=($currentDate->year-1).'/'.($currentDate->year);
        return view('lecturer.dashboard',['academicYear'=>$academicYear,'dateOfToday'=>$dateOfToday]);
        }

    public function login(Request $request){
        $validatedData=  $request->validate([
            'username'=>['required','string'],
            'password'=>['required','string'],
        ]);

        $check= $request->only('username','password');
        if(Auth::guard('lecturer')->attempt(['username'=>$check['username'],'password'=>$check['password']])){
            $request->session()->regenerate();
            return redirect()->route('lecturer.dashboard');
        }
        
            return back()->with('error','These credentials do not match our records.');
        
    }
    public function destroy(Request $request)
    {
        Auth::guard('lecturer')->logout();
        return redirect()->route('lecturer.login.form');
    }

    
    public function create(): View
    {
        return view('auth.lecturer_forgot_password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:lecturers,email' ],
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
        
        $action_link = route('lecturer.password.reset',['token'=>$token,'email'=>$request->email]);
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
        return view('auth.lecturer_reset_password', [
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
            'email'=>'required|email|exists:lecturers,email',
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

            Lecturer::where('email', $request->email)->update([
                'password'=>Hash::make($request->password)
            ]);

            \DB::table('password_reset_tokens')->where([
                'email'=>$request->email
            ])->delete();

            return redirect()->route('lecturer.login.form')->with('info', 'Your password has been changed! You can login with new password')->with('verifiedEmail', $request->email);
        }
    }

/***
 * Registration, delete, update and  search
 */
    public function registerLecturer(){
        $lecturers=Lecturer::paginate(10);
        return view('admin.lecturer.register_lecturer')->with('lecturers', $lecturers);
    }
    public function lecturerRegForm(){
        $departments = Department::all();
        return view('admin.lecturer.new_lecturer',['departments'=>$departments]);
    }
    
    public function lecturerEditForm($username){
        $username = str_replace('-', '/', $username);
        $departments = Department::all();
        $lecturer = lecturer::where('username', $username)->first();
        return view('admin.lecturer.edit_lecturer',['lecturer'=> $lecturer,'departments'=>$departments]);
    }
    
    
    public function storeLecturer(Request $request){
        $request->validate([
            'admission' => ['required', 'string', 'max:255','unique:'.Lecturer::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Lecturer::class],
            'firstname'=>['required','string'],
            'middlename'=>['required','string'],
            'lastname'=>['required','string'],
            'gender' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:12'],
            'nationality' => ['required', 'string', 'max:255'],
            'maritalstatus' => ['required', 'string', 'max:255'],
            'dob'=>['required','string'],
            'department'=>['required','string'],
        ]);
        $lecturers = Lecturer::where('department',$request->department)
        ->orderBy('username','desc')->first();
        if($lecturers && $lecturers->username){
            $array=explode('/',$lecturers->username);
            $nextLecturerId=intval($array[3])+1;
            $regno="NIT/".$request->department."/".date('Y')."/". $nextLecturerId;
        }
        else{
        $regno = "NIT/" . $request->department. "/" .date('Y'). "/1"; 
        }
         Lecturer::create([
            'username' => $regno,
            'admission'=>$request->admission,
            'email' => $request->email,
            'password' => Hash::make(strtoupper($request->lastname)),
            'firstname'=>ucfirst($request->firstname),
            'middlename'=>ucfirst($request->middlename),
            'lastname'=>ucfirst($request->lastname),
            'gender' => $request->gender,
            'phone' => $request->phone,
            'nationality' => $request->nationality,
            'maritalstatus' => $request->maritalstatus,
            'department'=>$request->department,
            'dob'=>$request->dob,
        ]);
        return redirect()->route('register.lecturer')->with('info','lecturer Registered successfully');
    }
    
    public function updateLecturer(Request $request, $username){
        $username = str_replace('-','/',$username);
        $request->validate([
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:lecturers,email,'.$username.',username'],
            'firstname' => ['required', 'string'],
            'middlename' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'gender' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:12'],
            'nationality' => ['required', 'string', 'max:255'],
            'maritalstatus' => ['required', 'string', 'max:255'],
            'department'=>['required','string'],
            'dob' => ['required', 'string'],
        ]);
        
        $lecturer = Lecturer::where('username', $username)->firstOrFail();
    
        $lecturer->update([
            'email' => $request->filled('email') ? $request->email : $lecturer->email,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'nationality' => $request->nationality,
            'maritalstatus' => $request->maritalstatus,
            'department'=>$request->department,
            'dob' => $request->dob,
        ]);
        
        return redirect()->route('register.lecturer')->with('info','Lecturer updated successfully');
    }
    
    
    public function search(Request $request)
    {
        $search = $request->query('username');
        if (!is_null($search)){
        $lecturers = Lecturer::where('username', 'like', '%'.$search.'%')->paginate(10);
        return view('admin.lecturer.register_lecturer')->with('lecturers',$lecturers);
        }
        return view('admin.lecturer.register_lecturer');
    }
    
    public function destroyLecturer($username){
    $username = str_replace('-', '/', $username);
    $lecturer = Lecturer::where('username', $username)->first();
    $lecturer->delete();
    return back()->with('info', 'Lecturer deleted successfully');
    }

    public function changePassword(){
        return view('lecturer.password');
    } 

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate( [
            'current_password' => ['required', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        $user = Auth::guard('lecturer')->user();
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error','The current password is incorrect.');
        }
     
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return back()->with('status', 'password-updated');
        
        
    }
    
    public function fullProfile(){
        $user = Auth::guard('lecturer')->user();
        return view('lecturer.profile')->with('user',$user);
       } 
       public function profileUpdate(){
        $user = Auth::guard('lecturer')->user();
        return view('lecturer.edit_profile')->with('user',$user);
       }
    
        public function storeProfile(Request $request)
        {
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:2048'], // Max file size is 2MB
        ]);
    
        $user = Auth::guard('lecturer')->user();
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
}
