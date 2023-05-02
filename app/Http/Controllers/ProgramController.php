<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Department;
use App\Http\Middleware\NoCacheMiddleware;

class ProgramController extends Controller
{
  
    //Disable caches using no cache middleware
    public function __construct()
    {
        $this->middleware(NoCacheMiddleware::class);
    }

    public function registerProgram(){
    $programs = Program::paginate(10);
    return view('admin.program.register_program')->with('programs',$programs);
    } 
    public function programRegForm(){
        $departments = Department::all(); 
        return view('admin.program.new_program',[ 'departments'=>$departments]);
    }
    public function programEditForm($programID){
        $departments = Department::all(); 
        $program = Program::where('programID', $programID)->first();
        return view('admin.program.edit_program',['program'=>$program,'departments'=>$departments]);
    }
    public function storeprogram(Request $request){
        $request->validate([
            'programID' => ['required', 'string', 'max:255', 'unique:'.Program::class],
            'programname' => ['required', 'string', 'max:255'],
            'ntalevel'=>['required','integer'],
            'capacity'=>['required','integer'],
            'department'=>['required','string'],
        ]);

        $program = Program::create([
            'programID' => strtoupper($request->programID),
            'programname'=>$request->programname,
            'ntalevel'=>$request->ntalevel,
            'capacity'=>$request->capacity,
            'department'=>$request->department,
        ]);
        
        return redirect()->route('register.program')->with('info','Program Registered successfully');
    }

    public function updateprogram(Request $request,$programID){
        $request->validate([
            // 'programID' => ['required', 'string', 'max:255', 'unique:'.Program::class],
            'programname' => ['required', 'string', 'max:255'],
            'ntalevel'=>['required','integer'],
            'capacity'=>['required','integer'],
            'department'=>['required','string'],
        ]);
        Program::where('programID', $programID)->update([
            // 'programID' => strtoupper($request->programID),
            'programname'=>$request->programname,
            'ntalevel'=>$request->ntalevel,
            'capacity'=>$request->capacity,
            'department'=>$request->department,
        ]);
        return redirect()->route('register.program')->with('info','Program updated successfully');
    }

    public function search(Request $request)
    {
        $search = $request->query('programID');
        if (!is_null($search)){
        $programs = Program::where('programID', 'like', '%'.$search.'%')->paginate(10);
        return view('admin.program.register_program')->with('programs',$programs);
        }
        return view('admin.program.register_program');
    }

    public function destroyprogram($programID){
    $program = Program::where('programID', $programID)->first();
    $program->delete();
    return back()->with('info', 'Program deleted successfully');
    }

}
