<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Lecturer;
use App\Models\Department;
use App\Models\Program;
use App\Http\Middleware\NoCacheMiddleware;
use DB;

class ModuleController extends Controller
{
    //Disable caches using no cache middleware
    public function __construct()
    {
        $this->middleware(NoCacheMiddleware::class);
    }

    public function registerModule(){
    $modules = Module::paginate(10);
    return view('admin.module.register_module')->with('modules',$modules);
    } 
    public function moduleRegForm() {
        $lecturers = Lecturer::all(); 
        $departments = Department::all(); 
        $programs = Program::all(); 
        return view('admin.module.new_module', ['lecturers' => $lecturers, 
        'departments'=>$departments,'programs' => $programs]);
    }
    
    
    public function moduleEditForm($modulecode){
        $lecturers = Lecturer::all(); 
        $departments = Department::all(); 
        $programs = Program::all(); 
        $module = module::where('modulecode', $modulecode)->first();
        return view('admin.module.edit_module',['module'=>$module,'lecturers' => $lecturers, 
        'departments'=>$departments, 'programs' => $programs]);
    }
    public function storemodule(Request $request){
        $request->validate([
            'modulecode' => ['required', 'string', 'max:255', 'unique:'.Module::class],
            'modulename' => ['required', 'string', 'max:255'],
            'credit'=>['required','integer'],
            'elective'=>['required','string'],
            'department'=>['required','string'],
            'program'=>['required','string'],
            'semester' => ['required', 'in:1,2'],
            'LecturerID'=>['nullable','string'],
        ]);

        $module = module::create([
            'modulecode' => strtoupper($request->modulecode),
            'modulename'=>$request->modulename,
            'credit'=>$request->credit,
            'elective'=>$request->elective,
            'department'=>$request->department,
            'program'=>$request->program,
            'semester'=>$request->semester,
            'lecturerID'=>$request->lecturerID,
        ]);
        
        return redirect()->route('register.module')->with('info','Module Registered successfully');
    }

    public function updatemodule(Request $request,$modulecode){
        $validatedData = $request->validate([
            // 'modulecode' => ['required', 'string', 'max:255', 'unique:'.module::class],
            'modulename' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'integer'],
            'elective' => ['required', 'string'],
            'department' => ['required', 'string'],
            'program' => ['required', 'string'],
            'semester' => ['required', 'in:1,2'],
            'lecturerID' => ['required', 'string'],
        ]);
        module::where('modulecode', $modulecode)->update([
            // 'modulecode' => strtoupper($request->modulecode),
            'modulename'=>$request->modulename,
            'credit'=>$request->credit,
            'elective'=>$request->elective,
            'department'=>$request->department,
            'program'=>$request->program,
            'semester'=>$request->semester,
            'lecturerID'=>$request->lecturerID,
        ]);
        return redirect()->route('register.module')->with('info','Module updated successfully');
    }

    public function search(Request $request)
    {
        $search = $request->query('modulecode');
        if (!is_null($search)){
        $modules = module::where('modulecode', 'like', '%'.$search.'%')->paginate(10);
        return view('admin.module.register_module')->with('modules',$modules);
        }
        return view('admin.module.register_module');
    }

    public function destroymodule($modulecode){
    $module = module::where('modulecode', $modulecode)->first();
    $module->delete();
    return back()->with('info', 'Module deleted successfully');
    }

}