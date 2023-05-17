<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery; 
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EnrollmentExport implements FromCollection,ShouldAutoSize,WithHeadings
{
    use Exportable;

    private $students;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($students){
       $this->students=$students;
    }
    public function collection()
    {
       return $this->students;
    }
    public function headings(): array
    {
        return [
            'Registration Number',
            'First Name',
            'Last Name'
        ];
    }
}
