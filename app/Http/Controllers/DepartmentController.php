<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Middleware\NoCacheMiddleware;

class DepartmentController extends Controller
{
     //Disable caches using no cache middleware
     public function __construct()
     {
         $this->middleware(NoCacheMiddleware::class);
     }

     public function registerDepartment(){
        $departments = Department::paginate(10);
        return view('admin.department.register_department')->with('departments',$departments);
    } 
    public function departmentRegForm(){
        return view('admin.department.new_department');
    }
    public function departmentEditForm($deptcode){
        $department = Department::where('deptcode', $deptcode)->first();
        return view('admin.department.edit_department')->with('department',$department);
    }
    public function storedepartment(Request $request){
        $request->validate([
            'deptcode' => ['required', 'string', 'max:255', 'unique:'.Department::class],
            'deptname' => ['required', 'string', 'max:255'],
        ]);

        $department = Department::create([
            'deptcode' => strtoupper($request->deptcode),
            'deptname' => strtoupper($request->deptname),
        ]);
        
        return redirect()->route('register.department')->with('info','Department Registered successfully');
    }

    public function updatedepartment(Request $request,$deptcode){
        $request->validate([
            // 'deptcode' => ['required', 'string', 'max:255','unique:'.Department::class],
            'deptname' => ['required', 'string', 'max:255'],
        ]);
        Department::where('deptcode', $deptcode)->update([
            // 'deptcode' => strtoupper($request->deptcode),
            'deptname' => strtoupper($request->deptname),
            
        ]);
        return redirect()->route('register.department')->with('info','Department updated successfully');
    }
    public function search(Request $request)
    {
        $search = $request->query('deptcode');
        if (!is_null($search)){
        $departments = Department::where('deptcode', 'like', '%'.$search.'%')->paginate(10);
        return view('admin.department.register_department')->with('departments',$departments);
        }
        return view('admin.department.register_department');
    }

    public function destroydepartment($deptcode){
    $department = Department::where('deptcode', $deptcode)->first();
     $department->delete();
    return back()->with('info', 'Department deleted successfully');
    }
}