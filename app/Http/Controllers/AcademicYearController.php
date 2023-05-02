<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Http\Middleware\NoCacheMiddleware;

class AcademicYearController extends Controller

{    //Disable caches using no cache middleware
    public function __construct()
    {
        $this->middleware(NoCacheMiddleware::class);
    }
    
    public function index(){
        $currentAcademicYear = AcademicYear::where('current', true)->first();
        return view('admin.academic_year.edit',['academicYear'=>$currentAcademicYear]);
    }
    public function create(){
        return view('admin.academic_year.new');
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'year' => ['required', 'regex:/^\d{4}\/\d{4}$/'], // ensure the year is in the format 2020/2021
            'semester' => ['required', 'in:1,2'],
        ]);

        $academicYear = AcademicYear::first();
        $academicYear->year = $request->input('year');
        $academicYear->semester = $request->input('semester');
        $academicYear->save();

        return back()->with('success', 'Academic year updated successfully!');
    }

    public function store(Request $request)
    {   
         // Validate the incoming request data
        $validatedData = $request->validate([
            'year' => ['required', 'regex:/^\d{4}\/\d{4}$/'], // ensure the year is in the format 2020/2021
            'semester' => ['required', 'in:1,2'],
        ]);

        // Get the current academic year
        $currentAcademicYear = AcademicYear::where('current', true)->first();

        // Update the current academic year to false
        if ($currentAcademicYear) {
            $currentAcademicYear->current = false;
            $currentAcademicYear->save();
        }

        // Create a new academic year record
        $newAcademicYear = new AcademicYear();
        $newAcademicYear->year = $request->input('year');
        $newAcademicYear->semester = $request->input('semester');
        $newAcademicYear->current = true;
        $newAcademicYear->save();

        return redirect()->back()->with('success', 'Academic year added successfully!');
    }

}
