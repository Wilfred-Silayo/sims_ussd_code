<?php

namespace App\Imports;

use App\Models\Enrollment;
use Illuminate\Support\Collection;
use App\Models\AcademicYear;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;
use DB;

class ResultsImport implements ToCollection,WithHeadingRow,SkipsOnFailure,SkipsOnError
{
    use SkipsErrors, SkipsFailures;

    private $currentAcademicYear;
    private $currentYear;
    private $currentSemester;
    private $moduleCode;

    public function __construct($moduleCode){
        $this->currentAcademicYear=AcademicYear::where('current', true)->first();
        $this->currentYear=$this->currentAcademicYear->year;
        $this->currentSemester=$this->currentAcademicYear->semester;
        $this->moduleCode=$moduleCode;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    
    public function collection(Collection $rows)
    {
  
    foreach($rows as $row){
        DB::table('enrollment')->where('studentID', $row['registration_number'])
        ->where('moduleCode', $this->moduleCode)->where('semester', $this->currentSemester)
        ->where('academicYear', $this->currentYear)
        ->update(['Coursework'=>$row['course_work'],'semesterExam'=>$row['semester_exam']]);
    }
    }


    public function onError(Throwable $error)
    {
    }
    public function onFailure(Failure ...$failure)
    {
    }
}
