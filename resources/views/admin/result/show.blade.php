@extends('layouts.admin')
@section('title','Results')
@section('content')

<div class="container">
    <div class="row">
        <h3>Results</h3>
        <hr>
    </div>
    <h5>Registered for : {{ $programName }} [{{ $programID }}]</h5>
    <h5>Student Registration : {{ $studentID}} </h5>
    @foreach($results as $result)
    <p>Academic Year: {{ $result['academicYear'] }}</p>
    @if($result['semesterOneResults']->count() > 0 || $result['semesterTwoResults']->count() > 0)
    @if($result['semesterOneResults']->count() > 0)
    <h5 class="text-primary">Semester 1 Results</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Unit</th>
                <th>CA</th>
                <th>SE</th>
                <th>Total</th>
                <th>Grade</th>
                <th>Point</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result['semesterOneResults'] as $semesterOneResult)
            <tr>
                <td>{{ $semesterOneResult->modulecode }}</td>
                <td>{{ $semesterOneResult->modulename }}</td>
                <td>{{ $semesterOneResult->credit }}</td>
                <td>{{ $semesterOneResult->Coursework }}</td>
                <td>{{ $semesterOneResult->semesterExam }}</td>
                <td>{{ $semesterOneResult->Coursework + $semesterOneResult->semesterExam  }}</td>
                <td>{{ $semesterOneResult->letterGrade }}</td>
                <td>{{ $semesterOneResult->gradePoint * $semesterOneResult->credit  }}</td>
                <td>{{ $semesterOneResult->remark }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="border-end-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0">Semester GPA</td>
                <td>{{ number_format($result['semesterOneGPA'], 2) }}</td>
                <td>@if($result['semesterOneGPA'] < 2.00) Discontinued @else Pass @endif </td>
            </tr>
        </tbody>
    </table>
    @endif
    @if($result['semesterTwoResults']->count() > 0)
    <h5 class="text-primary">Semester 2 Results</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Unit</th>
                <th>CA</th>
                <th>SE</th>
                <th>Total</th>
                <th>Grade</th>
                <th>Point</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result['semesterTwoResults'] as $semesterTwoResult)
            <tr>
                <td>{{ $semesterTwoResult->modulecode }}</td>
                <td>{{ $semesterTwoResult->modulename }}</td>
                <td>{{ $semesterTwoResult->credit }}</td>
                <td>{{ $semesterTwoResult->Coursework }}</td>
                <td>{{ $semesterTwoResult->semesterExam }}</td>
                <td>{{ $semesterTwoResult->Coursework + $semesterTwoResult->semesterExam  }}</td>
                <td>{{ $semesterTwoResult->letterGrade }}</td>
                <td>{{ $semesterTwoResult->gradePoint * $semesterTwoResult->credit  }}</td>
                <td>{{ $semesterTwoResult->remark }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="border-end-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0"></td>
                <td class=" border-0">Semester GPA</td>
                <td>{{ number_format($result['semesterTwoGPA'], 2) }}</td>
                <td>@if($result['semesterTwoGPA'] < 2.00) Discontinued @else Pass @endif </td>
            </tr>
        </tbody>
    </table>
    @endif
    @if($result['semesterOneResults']->count() > 0 && $result['semesterTwoResults']->count() > 0)
    <div class="col-md-6">
    <table class="table table-sm table-bordered">
        <tr>
            YEAR SUMMARY STATUS - {{$result['academicYear']}}
        </tr>
        <tbody>
            <tr>
            <td>Total Unit: </td>
            <td>{{$result['totalCreditsForYear']}}</td> 
            </tr>
            <tr>
            <td>Total Point: </td>
            <td>{{$result['totalGradePointsForYear']}}</td> 
            </tr>
            <tr>
            <td>Annual GPA: </td>
            <td>{{ $result['annualGPA'] }}</td> 
            </tr>
            <tr>
            <td>Annual Remark: </td>
            <td>{{ $result['annualRemark'] }}</td> 
            </tr>
        
        </tbody>
    </table>
    </div>
    @endif
    @else
    <hr>
    <p class="text-danger"> No Results Published</p>
    @endif
    @endforeach
   
</div>
@endsection