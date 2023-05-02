<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Program;
use App\Models\Module;
use Maatwebsite\Excel\Facades\Excel;
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
        return view('admin.result.result_list');
    }

    public function resultsUpload(){
        $lecturer=Auth::guard('lecturer')->user()->username;
        $modules=Module::where('lecturerID',$lecturer)->paginate(10);
        return view('lecturer.results.list_modules',compact('modules'));
    }
   
    public function edit($moduleCode, $studentID)
    {
        $enrollment = Enrollment::findOrFail([$moduleCode, $studentID]);
        return view('enrollment.edit', compact('enrollment'));
    }

    public function update(Request $request, $moduleCode, $studentID)
    {
        $enrollment = Enrollment::findOrFail([$moduleCode, $studentID]);

        $validatedData = $request->validate([
            'coursework' => 'required|numeric',
            'semester_exam' => 'required|numeric',
        ]);

        $enrollment->Coursework = $validatedData['coursework'];
        $enrollment->semesterExam = $validatedData['semester_exam'];

        $enrollment->save();

        return redirect()->route('enrollment.show', [$enrollment->moduleCode, $enrollment->studentID]);
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
    //excel
    public function importResults(Request $request,$moduleCode)
    {

        // Get the uploaded file
        $file = $request->file('file');
        
        // Read the Excel file into a collection
        $data = Excel::toCollection(null, $file);
        
        // Get the column headers for the sheet
        $headers = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $rowHeaders = $data[0][$i - 1]->toArray();
        
            if (in_array('CA', $rowHeaders) && in_array('SE', $rowHeaders) && in_array('reg_no', $rowHeaders)) {
                // Found the row with the headers, use it
                $headers = $rowHeaders;
                break;
            }
        }
        
        if (!empty($headers)) {
            // Both "CA" and "SE" columns exist, get the scores and registration number for each
            $caIndex = array_search('CA', $headers);
            $seIndex = array_search('SE', $headers);
            $regNoIndex = array_search('reg_no', $headers);
            
            $scores = [];
        
            foreach ($data[0]->skip(count($headers)) as $row) {
                $scores[] = [
                    'reg_no' => $row[$regNoIndex],
                    'ca_score' => $row[$caIndex],
                    'se_score' => $row[$seIndex],
                ];
            }
        
            // Process the scores by saving them to the database
            foreach ($scores as $score) {
                $enrollment = Enrollment::where('studentID', $score['reg_no'])
                    ->where('moduleCode', $moduleCode) 
                    ->first();
        
                if ($enrollment) {
                    // Update the existing record
                    $enrollment->Coursework = $score['ca_score'];
                    $enrollment->semesterExam = $score['se_score'];
                    $enrollment->save();
                } else {
                    // Create a new record
                    $enrollment = Enrollment();
                    $enrollment->studentID = $score['reg_no'];
                    $enrollment->moduleCode = $moduleCode; // replace with the actual module code
                    $enrollment->semester = $semester; // replace with the actual semester
                    $enrollment->academicyear = $academicYear; // replace with the actual academic year
                    $enrollment->Coursework = $score['ca_score'];
                    $enrollment->semesterExam = $score['se_score'];
                    $enrollment->published = false;
                    $enrollment->save();
                }
            }
        
            return back()->with('success', 'Enrollment records created/updated successfully.');
        } else {
            // The columns do not exist in any of the first five rows, handle accordingly
            // ...
            return back()->with('error', 'The reg_no, CA and SE do not exist in any of the first five rows');
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

    
    




