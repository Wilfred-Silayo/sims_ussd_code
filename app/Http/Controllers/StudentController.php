<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Program;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\NoCacheMiddleware;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use Carbon\Carbon;
use Auth;

class StudentController extends Controller
{
 //Disable caches using no cache middleware
 public function __construct()
 {
     $this->middleware(NoCacheMiddleware::class);
 }

public function index(){
    $dateOfToday=Carbon::now()->format('F d, Y');
    $currentAcademicYear = AcademicYear::where('current', true)->first();
    $student = Student::where('username', Auth::guard('web')->user()->username)->first();
    $programID = $student->program;
    $programName = Program::where('programID', $programID)->value('programname');
    return view('student.dashboard',['academicYear'=>$currentAcademicYear,'dateOfToday'=>$dateOfToday,
        'programID'=>$programID,'programName'=>$programName,
]);
}

public function registration(){
    $user=Auth::user();
    return view('student.registration',['user'=>$user]);
}
public function courseRegistration(){
    $student = Auth::user()->username;
    $currentAcademicYear = AcademicYear::where('current', true)->first();
    $currentSemester = $currentAcademicYear->semester; 
    $currentYear = $currentAcademicYear->year;   

    $nonElectiveCourses= Enrollment::where('studentID', $student)
    ->where('enrollment.semester', $currentSemester)
    ->where('academicYear', $currentYear)
    ->join('modules', 'enrollment.moduleCode', '=', 'modules.modulecode')
    ->select('enrollment.*', 'modules.modulename', 'modules.credit')
    ->get();
    
    $electiveCourses = DB::table('modules')
    ->select('modules.modulecode', 'modules.modulename', 'modules.credit')
    ->where('modules.program', Auth::user()->program)
    ->where('modules.elective', 'yes')
    ->where('modules.semester', $currentSemester)
    ->get();

    
    // Get the enrolled modules for the current student and semester
    $enrolledModules = Enrollment::where('studentID', $student)
        ->where('semester', $currentSemester)
        ->where('academicYear', $currentYear)
        ->pluck('modulecode')
        ->toArray();


  
    return view('student.course_registration', [
        'nonElectiveCourses' => $nonElectiveCourses, 'currentYear'=> $currentYear,
        'currentSemester'=> $currentSemester,  'electiveCourses'=>$electiveCourses,
        'enrolledModules' => $enrolledModules,
    ]);
}


 public function registerStudent(){
    $students=Student::paginate(10);

    return view('admin.student.register_student')->with('students', $students);
}
public function studentRegForm(){
    $programs = Program::all();
    return view('admin.student.new_student',['programs'=>$programs]);
}

public function studentEditForm($username){
    $username = str_replace('-', '/', $username);
    $programs = Program::all();
    $student = Student::where('username', $username)->first();
    return view('admin.student.edit_student',['student'=> $student,'programs'=>$programs]);
}


public function storeStudent(Request $request){
    $request->validate([
        'admission' => ['required', 'string', 'max:255','unique:'.Student::class],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Student::class],
        'firstname'=>['required','string'],
        'middlename'=>['required','string'],
        'lastname'=>['required','string'],
        'gender' => ['required', 'string', 'max:10'],
        'phone' => ['required', 'string', 'max:12'],
        'nationality' => ['required', 'string', 'max:255'],
        'maritalstatus' => ['required', 'string', 'max:255'],
        'program' => ['required', 'string', 'max:255'],
        'dob'=>['required','string'],
    ]);
    
    $regno='';
    $students = Student::where('program',$request->program)
    ->orderBy('username','desc')->first();
    if($students && $students->username){
        $array = explode('/', $students);
        $nextStudentId = intval($array[3]) + 1;
        $regno = "NIT/" . $request->program . "/" . date('Y'). "/" . $nextStudentId;
    } else {
        $regno = "NIT/" . $request->program . "/" . date('Y'). "/1"; 
    }
    Student::create([
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
        'program' => $request->program,
        'dob'=>$request->dob,
        'yearofstudy' =>date('Y'),
    ]);
    return redirect()->route('register.student')->with('info','Student Registered successfully');
}

public function updateStudent(Request $request, $username){
    $username = str_replace('-','/',$username);
    $request->validate([
        'email' => ['nullable', 'string', 'email', 'max:255', 'unique:students,email,'.$username.',username'],
        'firstname' => ['required', 'string'],
        'middlename' => ['required', 'string'],
        'lastname' => ['required', 'string'],
        'gender' => ['required', 'string', 'max:10'],
        'phone' => ['required', 'string', 'max:12'],
        'nationality' => ['required', 'string', 'max:255'],
        'maritalstatus' => ['required', 'string', 'max:255'],
        'program' => ['required', 'string', 'max:255'],
        'dob' => ['required', 'string'],
    ]);
    
    $student = Student::where('username', $username)->firstOrFail();

    $student->update([
        'email' => $request->filled('email') ? $request->email : $student->email,
        'firstname' => $request->firstname,
        'middlename' => $request->middlename,
        'lastname' => $request->lastname,
        'gender' => $request->gender,
        'phone' => $request->phone,
        'nationality' => $request->nationality,
        'maritalstatus' => $request->maritalstatus,
        'program' => $request->program,
        'dob' => $request->dob,
    ]);
    
    return redirect()->route('register.student')->with('info','Student updated successfully');
}


public function search(Request $request)
{
    $search = $request->query('username');
    if (!is_null($search)){
    $students = Student::where('username', 'like', '%'.$search.'%')->paginate(10);
    return view('admin.student.register_student')->with('students',$students);
    }
    return view('admin.student.register_student');
}

public function destroystudent($username){
$username = str_replace('-', '/', $username);
$student = student::where('username', $username)->first();
 $student->delete();
return back()->with('info', 'Student deleted successfully');
}
   
}
