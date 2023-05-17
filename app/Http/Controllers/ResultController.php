<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Program;
use App\Models\Module;
use App\Models\AcademicYear;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ResultsImport;
use App\Exports\EnrollmentExport;
use App\Http\Middleware\NoCacheMiddleware;
use Auth;

class ResultController extends Controller{
 //Disable caches using no cache middleware
    public function __construct()
    {
        $this->middleware(NoCacheMiddleware::class);
    }

    public function index(){
        $results = DB::table('students')->paginate(10);
        return view('admin.result.result_list',['results'=> $results]);
    }
    public function search(Request $request)
    {
        $search = $request->query('studentID');
        if (!is_null($search)){
        $results = Student::where('username', 'like', '%'.$search.'%')->paginate(10);
        return view('admin.result.result_list')->with('results',$results);
        }
        return back()->with('error','Please enter search query');
    }

    public function resultsUpload(){

        $currentSemester=AcademicYear::where('current',true)->first()->semester;
        $currentYear=AcademicYear::where('current',true)->first()->year;
        $lecturer=Auth::guard('lecturer')->user()->username;
        $modules = DB::table('modules')
        ->join('enrollment', 'modules.modulecode', '=', 'enrollment.moduleCode')
        ->where('modules.lecturerID', '=', $lecturer)
        ->where('modules.semester', '=', $currentSemester)
        ->where('enrollment.academicYear', '=', $currentYear)
        ->where('enrollment.semester', '=', $currentSemester)
        ->select('modules.*')
        ->distinct()->paginate(10);
        
        return view('lecturer.results.list_modules',compact('modules'));
    }
    
    public function resultsAdd($moduleCode,$moduleName){
        return view('lecturer.results.new',compact('moduleCode','moduleName'));
    }

    public function newResult(Request $request, $moduleCode){
        $request->validate([
            'studentID' => 'required|string',
            'Coursework' => 'required|numeric',
            'semesterExam' => 'required|numeric',
        ]);

        $result=Enrollment::where('moduleCode', $moduleCode)
          ->where('studentID', $request->studentID)
          ->update(['Coursework' => $request->Coursework, 'semesterExam' => $request->semesterExam]);
        if($result){
        return back()->with('success',"result added Successfully");
        }
        return back()->with('error',"student not found");
    }

    public function excelUpload(Request $request,$moduleCode){
        $request->validate([
            'file'=>['required','mimes:xls,xlsx']
        ]);

        $file=$request->file(key:'file');
        Excel::import(new ResultsImport($moduleCode),$file);
        return back()->with('success','results Imported successfully');
    }

    public function excelDownload(Request $request,$moduleCode){
        $currentSemester=AcademicYear::where('current',true)->first()->semester;
        $currentYear=AcademicYear::where('current',true)->first()->year;

        $students=DB::table('enrollment')->where('moduleCode',$moduleCode)
        ->where('academicYear',$currentYear)->where('semester',$currentSemester)
        ->join('students','enrollment.studentID','=','students.username')
        ->select('enrollment.studentID','students.firstname','students.lastname')->get();

        return (new EnrollmentExport($students))->download($moduleCode.'.xlsx');
        
    }

    public function viewStudents($moduleCode,$moduleName){
        $currentSemester=AcademicYear::where('current',true)->first()->semester;
        $currentYear=AcademicYear::where('current',true)->first()->year;
        
        $students=DB::table('enrollment')->where('moduleCode',$moduleCode)
        ->where('academicYear',$currentYear)->where('semester',$currentSemester)
        ->join('students','enrollment.studentID','=','students.username')
        ->select('enrollment.studentID','students.firstname','students.lastname')->paginate(10);
        
        return view('lecturer.results.view_students',compact('moduleCode','moduleName','students'));
    }

    public function resultsView(){
        $currentSemester=AcademicYear::where('current',true)->first()->semester;
        $currentYear=AcademicYear::where('current',true)->first()->year;
        $lecturer=Auth::guard('lecturer')->user()->username;
        $modules = DB::table('modules')
        ->join('enrollment', 'modules.modulecode', '=', 'enrollment.moduleCode')
        ->where('modules.lecturerID', '=', $lecturer)
        ->where('modules.semester', '=', $currentSemester)
        ->where('enrollment.academicYear', '=', $currentYear)
        ->where('enrollment.semester', '=', $currentSemester)
        ->select('modules.*')
        ->distinct()->paginate(10);
        return view('lecturer.results.view_modules',compact('modules'));
    }

    public function viewStudentsResults($moduleCode,$moduleName){
        $currentSemester=AcademicYear::where('current',true)->first()->semester;
        $currentYear=AcademicYear::where('current',true)->first()->year;
        
        $students=DB::table('enrollment')->where('moduleCode',$moduleCode)
        ->where('academicYear',$currentYear)->where('semester',$currentSemester)->paginate(10);
        return view('lecturer.results.view_results',compact('students','moduleCode','moduleName'));
    }
 
    public function destroyresult($moduleCode, $studentID)
    {
    $studentID=str_replace('-','/',$studentID);
    // Find the enrollment record for the given moduleCode and studentID
    $enrollment = Enrollment::where('moduleCode', $moduleCode)->where('studentID', $studentID)->first();

    // If enrollment record exists, set the result to null and save the record
    Enrollment::where('moduleCode', $moduleCode)
          ->where('studentID', $studentID)
          ->update(['CourseWork' => null, 'semesterExam' => null]);
        return redirect()->back()->with('success', 'Result deleted successfully.');

    }

    public function searchStudentsResults(Request $request,$moduleCode,$moduleName)
    {
        $search = $request->query('studentID');
        if (!is_null($search)){
            $students=Enrollment::where('moduleCode',$moduleCode)
            ->where('studentID', 'like', '%'.$search.'%')->paginate(10);
        return view('lecturer.results.view_results',compact('moduleCode','moduleName','students'));
        }
        return back()->with('error', 'Please enter a search query.');
    }

    public function searchStudents(Request $request,$moduleCode,$moduleName)
    {
        $search = $request->query('studentID');
        if (!is_null($search)){
            $students=DB::table('enrollment')->where('moduleCode',$moduleCode)
            ->where('studentID', 'like', '%'.$search.'%')
            ->join('students','enrollment.studentID','=','students.username')
            ->select('enrollment.studentID','students.firstname','students.lastname')->paginate(10);
        return view('lecturer.results.view_students',compact('moduleCode','moduleName','students'));
        }
        return redirect()->back()->with('error', 'Please enter a search query.');
    }

   
    public function edit($moduleCode, $studentID,$moduleName)
    {
        $studentID=str_replace('-','/',$studentID);
        $result = Enrollment::where('moduleCode',$moduleCode)->where('studentID',$studentID)->first();
        return view('lecturer.results.edit', compact('result','moduleName'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'coursework' => 'required|numeric',
            'semester_exam' => 'required|numeric',
        ]);

        Enrollment::where('moduleCode', $request->moduleCode)
          ->where('studentID', $request->studentID)
          ->update(['Coursework' => $request->coursework, 'semesterExam' => $request->semester_exam]);

        return redirect()->route('lecturer.module.viewStudentsResults',
        ['moduleCode'=>$request->moduleCode,'moduleName'=>$request->moduleName])->with('success',"result updated Successfully");
    }
 
    public function publish()
    {
        $count = DB::table('enrollment')->count();
        if($count > 0){
            DB::table('enrollment')->update(['published' => true]);
            return redirect()->back()->with('success', 'Results published successfully!');
        } else {
            return redirect()->back()->with('error', 'No results to publish!');
        }
    }

    public function unpublish()
    {
        $count = DB::table('enrollment')->count();
        if($count > 0){
            DB::table('enrollment')->update(['published' => false]);
            return redirect()->back()->with('success', 'Results unpublished successfully!');
        } else {
            return redirect()->back()->with('error', 'No results to unpublish!');
        }
    }
    

    public function results()
    {   
    $student = Student::where('username', auth()->user()->username)->first();
    $programID = $student->program;
    $programName = Program::where('programID', $programID)->value('programname');

    //get unique academic years for the authenticated student
    $academicYears = DB::table('enrollment')
                        ->select('academicYear')
                        ->distinct()
                        ->where('studentID', auth()->user()->username)
                        ->pluck('academicYear');
    
    //initialize results array
    $results = [];
    
    //loop through each academic year
    foreach($academicYears as $year){
        //get results for first semester
        $semesterOneResults = DB::table('enrollment')
                                ->join('modules', 'enrollment.moduleCode', '=', 'modules.modulecode')
                                ->select('modules.modulecode', 'modules.modulename', 'enrollment.Coursework', 'enrollment.semesterExam', 'enrollment.published', 'modules.credit')
                                ->where('enrollment.studentID', auth()->user()->username)
                                ->where('enrollment.academicYear', $year)
                                ->where('enrollment.semester', 1)
                                ->where('enrollment.published', true)
                                ->get();
        
        //calculate GPA for first semester
        $totalCreditsForSemesterOne = 0;
        $totalGradePointsForSemesterOne = 0;
        foreach($semesterOneResults as $result){
            $moduleResult = $result->Coursework + $result->semesterExam;
            $gradePoint = 0;
            $letterGrade = '';
            $remark = '';
            if ($moduleResult >= 70) {
                $gradePoint = 5;
                $letterGrade = 'A';
                $remark = 'Pass';
            } elseif ($moduleResult >= 60) {
                $gradePoint = 4;
                $letterGrade = 'B+';
                $remark = 'Pass';
            } elseif ($moduleResult >= 50) {
                $gradePoint = 3;
                $letterGrade = 'B-';
                $remark = 'Pass';
            } elseif ($moduleResult >= 40) {
                $gradePoint = 2;
                $letterGrade = 'C';
                $remark = 'Pass';
            } elseif ($moduleResult >= 35) {
                $gradePoint = 1;
                $letterGrade = 'D';
                $remark = 'SUPP';
            } else {
                $letterGrade = 'F';
                $remark = 'Fail';
            }
                // add the grade point, letter grade, and remark to the result object
            $result->gradePoint = $gradePoint;
            $result->letterGrade = $letterGrade;
            $result->remark = $remark;
            $totalCreditsForSemesterOne += $result->credit;
            $totalGradePointsForSemesterOne += ($gradePoint * $result->credit);
        }
        $semesterOneGPA = $totalCreditsForSemesterOne > 0 ? round($totalGradePointsForSemesterOne / $totalCreditsForSemesterOne, 2) : 0;
        
        //get results for second semester
        $semesterTwoResults = DB::table('enrollment')
                                ->join('modules', 'enrollment.moduleCode', '=', 'modules.modulecode')
                                ->select('modules.modulecode', 'modules.modulename', 'enrollment.Coursework', 'enrollment.semesterExam', 'enrollment.published', 'modules.credit')
                                ->where('enrollment.studentID', auth()->user()->username)
                                ->where('enrollment.academicYear', $year)
                                ->where('enrollment.semester', 2)
                                ->where('enrollment.published', true)
                                ->get();
        
        //calculate GPA for second semester
        $totalCreditsForSemesterTwo = 0;
        $totalGradePointsForSemesterTwo = 0;
        foreach($semesterTwoResults as $result){
            $moduleResult = $result->Coursework + $result->semesterExam;
            $gradePoint = 0;
            $letterGrade = '';
            $remark = '';
            if ($moduleResult >= 70) {
                $gradePoint = 5;
                $letterGrade = 'A';
                $remark = 'Pass';
            } elseif ($moduleResult >= 60) {
                $gradePoint = 4;
                $letterGrade = 'B+';
                $remark = 'Pass';
            } elseif ($moduleResult >= 50) {
                $gradePoint = 3;
                $letterGrade = 'B-';
                $remark = 'Pass';
            } elseif ($moduleResult >= 40) {
                $gradePoint = 2;
                $letterGrade = 'C';
                $remark = 'Pass';
            } elseif ($moduleResult >= 35) {
                $gradePoint = 1;
                $letterGrade = 'D';
                $remark = 'SUPP';
            } else {
                $letterGrade = 'F';
                $remark = 'Fail';
            }
                // add the grade point, letter grade, and remark to the result object
            $result->gradePoint = $gradePoint;
            $result->letterGrade = $letterGrade;
            $result->remark = $remark;
            $totalCreditsForSemesterTwo += $result->credit;
            $totalGradePointsForSemesterTwo += ($gradePoint * $result->credit);
        }
        $semesterTwoGPA = $totalCreditsForSemesterTwo > 0 ? round($totalGradePointsForSemesterTwo / $totalCreditsForSemesterTwo, 2) : 0;

                //calculate annual GPA
        $totalCreditsForYear = $totalCreditsForSemesterOne + $totalCreditsForSemesterTwo;
        $totalGradePointsForYear = $totalGradePointsForSemesterOne + $totalGradePointsForSemesterTwo;
        $annualGPA = $totalCreditsForYear > 0 ? round($totalGradePointsForYear / $totalCreditsForYear, 2) : 0;
        if($annualGPA < 2) {
            $annualRemark = 'Discontinue';
        } else {
            $annualRemark = 'Pass';
        }
        //add results for academic year to array
        $results[] = [
            'academicYear' => $year,
            'programName' => $programName,
            'semesterOneResults' => $semesterOneResults,
            'semesterOneGPA' => $semesterOneGPA,
            'semesterTwoResults' => $semesterTwoResults,
            'semesterTwoGPA' => $semesterTwoGPA,
                'annualGPA'=>$annualGPA,
                'totalCreditsForYear'=>  $totalCreditsForYear,
                'totalGradePointsForYear'=>$totalGradePointsForYear,
                'annualRemark' => $annualRemark,
        ];

        }

        return view('student.results', compact('results', 'programID', 'programName'));
    }


    public function showResult($studentID)
    {
        $studentID = str_replace('-', '/', $studentID);
        $student=Student::where('username',$studentID)->first();
        $programID = $student->program;
        $programName = Program::where('programID', $programID)->value('programname');
    
        //get unique academic years for the authenticated student
        $academicYears = DB::table('enrollment')
                            ->select('academicYear')
                            ->distinct()
                            ->where('studentID', $studentID)
                            ->pluck('academicYear');
        
        //initialize results array
        $results = [];
        
        //loop through each academic year
        foreach($academicYears as $year){
            //get results for first semester
            $semesterOneResults = DB::table('enrollment')
                                    ->join('modules', 'enrollment.moduleCode', '=', 'modules.modulecode')
                                    ->select('modules.modulecode', 'modules.modulename', 'enrollment.Coursework', 'enrollment.semesterExam', 'enrollment.published', 'modules.credit')
                                    ->where('enrollment.studentID', $studentID)
                                    ->where('enrollment.academicYear', $year)
                                    ->where('enrollment.semester', 1)
                                    ->get();
            
            //calculate GPA for first semester
            $totalCreditsForSemesterOne = 0;
            $totalGradePointsForSemesterOne = 0;
            foreach($semesterOneResults as $result){
                $moduleResult = $result->Coursework + $result->semesterExam;
                $gradePoint = 0;
                $letterGrade = '';
                $remark = '';
                if ($moduleResult >= 70) {
                    $gradePoint = 5;
                    $letterGrade = 'A';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 60) {
                    $gradePoint = 4;
                    $letterGrade = 'B+';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 50) {
                    $gradePoint = 3;
                    $letterGrade = 'B-';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 40) {
                    $gradePoint = 2;
                    $letterGrade = 'C';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 35) {
                    $gradePoint = 1;
                    $letterGrade = 'D';
                    $remark = 'SUPP';
                } else {
                    $letterGrade = 'F';
                    $remark = 'Fail';
                }
                    // add the grade point, letter grade, and remark to the result object
                $result->gradePoint = $gradePoint;
                $result->letterGrade = $letterGrade;
                $result->remark = $remark;
                $totalCreditsForSemesterOne += $result->credit;
                $totalGradePointsForSemesterOne += ($gradePoint * $result->credit);
            }
            $semesterOneGPA = $totalCreditsForSemesterOne > 0 ? round($totalGradePointsForSemesterOne / $totalCreditsForSemesterOne, 2) : 0;
            
            //get results for second semester
            $semesterTwoResults = DB::table('enrollment')
                                    ->join('modules', 'enrollment.moduleCode', '=', 'modules.modulecode')
                                    ->select('modules.modulecode', 'modules.modulename', 'enrollment.Coursework', 'enrollment.semesterExam', 'enrollment.published', 'modules.credit')
                                    ->where('enrollment.studentID', $studentID)
                                    ->where('enrollment.academicYear', $year)
                                    ->where('enrollment.semester', 2)
                                    ->get();
            
            //calculate GPA for second semester
            $totalCreditsForSemesterTwo = 0;
            $totalGradePointsForSemesterTwo = 0;
            foreach($semesterTwoResults as $result){
                $moduleResult = $result->Coursework + $result->semesterExam;
                $gradePoint = 0;
                $letterGrade = '';
                $remark = '';
                if ($moduleResult >= 70) {
                    $gradePoint = 5;
                    $letterGrade = 'A';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 60) {
                    $gradePoint = 4;
                    $letterGrade = 'B+';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 50) {
                    $gradePoint = 3;
                    $letterGrade = 'B-';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 40) {
                    $gradePoint = 2;
                    $letterGrade = 'C';
                    $remark = 'Pass';
                } elseif ($moduleResult >= 35) {
                    $gradePoint = 1;
                    $letterGrade = 'D';
                    $remark = 'SUPP';
                } else {
                    $letterGrade = 'F';
                    $remark = 'Fail';
                }
                    // add the grade point, letter grade, and remark to the result object
                $result->gradePoint = $gradePoint;
                $result->letterGrade = $letterGrade;
                $result->remark = $remark;
                $totalCreditsForSemesterTwo += $result->credit;
                $totalGradePointsForSemesterTwo += ($gradePoint * $result->credit);
            }
            $semesterTwoGPA = $totalCreditsForSemesterTwo > 0 ? round($totalGradePointsForSemesterTwo / $totalCreditsForSemesterTwo, 2) : 0;

                    //calculate annual GPA
            $totalCreditsForYear = $totalCreditsForSemesterOne + $totalCreditsForSemesterTwo;
            $totalGradePointsForYear = $totalGradePointsForSemesterOne + $totalGradePointsForSemesterTwo;
            $annualGPA = $totalCreditsForYear > 0 ? round($totalGradePointsForYear / $totalCreditsForYear, 2) : 0;
            if($annualGPA < 2) {
                $annualRemark = 'Discontinue';
            } else {
                $annualRemark = 'Pass';
            }
            //add results for academic year to array
            $results[] = [
                'academicYear' => $year,
                'programName' => $programName,
                'semesterOneResults' => $semesterOneResults,
                'semesterOneGPA' => $semesterOneGPA,
                'semesterTwoResults' => $semesterTwoResults,
                'semesterTwoGPA' => $semesterTwoGPA,
                    'annualGPA'=>$annualGPA,
                    'totalCreditsForYear'=>  $totalCreditsForYear,
                    'totalGradePointsForYear'=>$totalGradePointsForYear,
                    'annualRemark' => $annualRemark,
            ];

            }

            return view('admin.result.show', compact('results', 'programID', 'programName','studentID'));
        }


    
}

    
    




