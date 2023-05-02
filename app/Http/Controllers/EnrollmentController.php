<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\AcademicYear;
use App\Models\Program;
use App\Models\Student;
use Auth;

class EnrollmentController extends Controller
{   
    public function index(){
        $enrollments=Enrollment::paginate(10);
        return view('admin.enrollment.view',['enrollments'=>$enrollments]);
    }


    // public function store(Request $request)
    // {
    //     $currentAcademicYear = AcademicYear::where('current', true)->first();
    //     $currentYear=$currentAcademicYear->year;
    //     $currentSemester= $currentAcademicYear->semester;
    
    //     // Retrieve the selected modules from the form input
    //     $selectedModules = $request->input('course', []);
    
    //     // Loop through the selected modules and create an enrollment record for each
    //     foreach ($selectedModules as $moduleCode) {
    //         // Check if enrollment already exists for this module by this user in this academic year and semester
    //         $enrollmentExists = Enrollment::where('studentID', Auth::user()->username)
    //             ->where('modulecode', $moduleCode)
    //             ->where('academicYear', $currentYear)
    //             ->where('semester', $currentSemester)
    //             ->exists();
    
    //         if (!$enrollmentExists) {
    //             $enrollment = new Enrollment;
    //             $enrollment->studentID = Auth::user()->username;
    //             $enrollment->modulecode = $moduleCode;
    //             $enrollment->semester = $currentSemester;
    //             $enrollment->academicYear = $currentYear;
    //             $enrollment->save();
    //         } else {
    //             // Return an error if an enrollment already exists for this module
    //             return back()->with('error', 'Enrollment for module ' . $moduleCode . ' already exists.');
    //         }
    //     }
    
    //     // Redirect back to the course registration page
    //     return back()->with('success', 'Enrollment successful.');
    // }
    
    public function store(Request $request)
    {
        $currentAcademicYear = AcademicYear::where('current', true)->first();
        $currentYear = $currentAcademicYear->year;
        $currentSemester = $currentAcademicYear->semester;
    
        // Retrieve the selected modules from the form input
        $selectedModules = $request->input('course', []);
                
           
        // Loop through the selected modules and create or delete enrollment records based on the checkbox value
        foreach ($selectedModules as $modulecode) {
            // Check if enrollment already exists
            $enrollment = Enrollment::where('studentID', Auth::user()->username)
                ->where('moduleCode', $modulecode)
                ->where('semester', $currentSemester)
                ->where('academicYear', $currentYear)
                ->first();

            if (in_array($modulecode, $selectedModules)) {
                if (!$enrollment) {
                    // Create enrollment record
                    $enrollment = new Enrollment;
                    $enrollment->studentID = Auth::user()->username;
                    $enrollment->moduleCode = $modulecode;
                    $enrollment->semester = $currentSemester;
                    $enrollment->academicYear = $currentYear;
                    $enrollment->save();
                }
            } else {
                if ($enrollment) {
                    // Delete enrollment record
                    $enrollment->delete();
                }
            }
        }


        // Redirect back to the course registration page
        return back()->with('success', 'Enrollment successful.');
    }
    






    public function search(Request $request)
    {
        $search = $request->query('studentID');
        if (!is_null($search)){
        $enrollments = Enrollment::where('studentID', 'like', '%'.$search.'%')->paginate(10);
        return view('admin.enrollment.view')->with('enrollments',$enrollments);
        }
        return view('admin.enrollment.view');
    }
  
    public function enroll(Request $request){
        $currentYear = AcademicYear::where('current', true)->first();
        if (empty($currentYear)) {
            return back()->with('error', 'There is no active academic year.');
        }
    
        $programs = Program::all();
    
        if ($programs->count() == 0) {
            return back()->with('error', 'There are no programs available for enrollment.');
        }
    
        $enrollmentsCount = 0;
    
        foreach ($programs as $program) {
            $modules = Module::where('program', $program->programID)
                            ->where('semester', $currentYear->semester)
                            ->where('elective', 'no')
                            ->get();
    
            if ($modules->count() == 0) {
                continue; // skip this program if there are no modules available for enrollment
            }
    
            $students = Student::where('program', $program->programID)
                                ->where('active', 'yes')
                                ->where('status', 'continuing')
                                ->get();
    
            if ($students->count() == 0) {
                continue; // skip this program if there are no students available for enrollment
            }
    
            foreach ($students as $student) {
                foreach ($modules as $module) {
                    $existingEnrollment = Enrollment::where('studentID', $student->username)
                    ->where('moduleCode', $module->modulecode)
                    ->where('academicYear', $currentYear->year)
                    ->where('semester', $currentYear->semester)
                    ->first();
    
                    if ($existingEnrollment) {
                        continue; // skip enrollment if the student is already enrolled
                    }
    
                    $enrollment = new Enrollment();
                    $enrollment->studentID = $student->username;
                    $enrollment->moduleCode = $module->modulecode;
                    $enrollment->semester = $currentYear->semester;
                    $enrollment->academicYear = $currentYear->year;
                    $enrollment->Coursework = null;
                    $enrollment->semesterExam = null;
                    $enrollment->published = false;
                    $enrollment->save();
    
                    $enrollmentsCount++;
                }
            }
        }
    
        if ($enrollmentsCount == 0) {
            return back()->with('error', 'No enrollments were made. It may be all students have 
            already enrolled or You should check if there are registered students and modules');
        }
    
        return back()->with('success', 'Enrollments have been successfully made.');
    }
    

}


