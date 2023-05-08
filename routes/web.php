<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AcademicYearController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');


/*************************** student routes ***************/
Route::prefix('dashboard')->middleware('auth')->group(function(){
Route::get('/',[StudentController::class,'index'])->name('student.dashboard'); 
Route::get('/registration',[StudentController::class,'registration'])->name('student.registration');
Route::get('/course/registration',[StudentController::class,'courseRegistration'])->name('student.course.registration');
Route::get('/results',[ResultController::class,'results'])->name('student.result');
Route::get('/fullProfile',[ProfileController::class,'profileUpdate'])->name('student.fullProfile');
Route::put('/fullProfile/store',[ProfileController::class,'storeProfile'])->name('student.profile.store');
Route::get('/password',[PasswordController::class,'updateForm'])->name('student.changePassword');
Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');
   
});
    
    /*************************** End student routes ***************/


/*************************** Admin routes ***************/
Route::middleware('guest')->prefix('admin')->group(function(){
Route::get('/login',[AdminController::class,'index'])->name('admin.login.form');
Route::post('/login/owner',[AdminController::class,'login'])->name('admin.login');

Route::get('forgot-password', [AdminController::class, 'create'])->name('admin.password.request');
Route::post('forgot-password', [AdminController::class, 'store'])->name('admin.password.email');
Route::get('reset-password/{token}', [AdminController::class, 'createReset'])->name('admin.password.reset');
Route::post('reset-password', [AdminController::class, 'storeReset'])->name('admin.password.store');
});

Route::prefix('admin')->middleware('admin')->group(function(){
Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
Route::post('/logout',[AdminController::class,'destroy'])->name('admin.logout');

//password
Route::get('/password',[AdminController::class,'changePassword'])->name('admin.changePassword'); 
Route::put('/password/update', [AdminController::class, 'update'])->name('admin.password.update');

// students
Route::get('register/student', [StudentController::class, 'registerStudent'])->name('register.student');
Route::get('register/student/form', [StudentController::class, 'studentRegForm'])->name('register.student.form');
Route::get('register/student/edit/{username}', [StudentController::class, 'studentEditForm'])->name('register.student.edit');
Route::delete('/student/destroy/{username}', [StudentController::class, 'destroyStudent'])->name('student.destroy');
Route::put('/student/update/{username}', [StudentController::class, 'updateStudent'])->name('student.update');
Route::post('register/student/save', [StudentController::class, 'storeStudent'])->name('student.store');
Route::get('/student/search', [StudentController::class, 'search'])->name('student.search');

//lecturer
Route::get('register/lecturer', [LecturerController::class, 'registerLecturer'])->name('register.lecturer');
Route::post('register/lecturer/save', [LecturerController::class, 'storeLecturer'])->name('lecturer.store'); 
Route::get('register/lecturer/form', [LecturerController::class, 'lecturerRegForm'])->name('register.lecturer.form');
Route::get('register/lecturer/edit/{username}', [LecturerController::class, 'lecturerEditForm'])->name('register.lecturer.edit');
Route::delete('/lecturer/destroy/{username}', [LecturerController::class, 'destroyLecturer'])->name('lecturer.destroy');
Route::put('/lecturer/update/{username}', [LecturerController::class, 'updateLecturer'])->name('lecturer.update');
Route::get('/lecturer/search', [LecturerController::class, 'search'])->name('lecturer.search');

//program
Route::get('register/program', [ProgramController::class, 'registerProgram'])->name('register.program');
Route::post('register/program/save', [ProgramController::class, 'storeProgram'])->name('program.store'); 
Route::get('register/program/form', [ProgramController::class, 'programRegForm'])->name('register.program.form');
Route::get('register/program/edit/{programID}', [ProgramController::class, 'programEditForm'])->name('register.program.edit');
Route::delete('/program/destroy/{programID}', [ProgramController::class, 'destroyprogram'])->name('program.destroy');
Route::put('/program/update/{programID}', [ProgramController::class, 'updateprogram'])->name('program.update');
Route::get('/program/search', [ProgramController::class, 'search'])->name('program.search');

//module
Route::get('register/module', [ModuleController::class, 'registerModule'])->name('register.module');
Route::post('register/module/save', [ModuleController::class, 'storeModule'])->name('module.store'); 
Route::get('register/module/form', [ModuleController::class, 'moduleRegForm'])->name('register.module.form');
Route::get('register/module/edit/{modulecode}', [ModuleController::class, 'moduleEditForm'])->name('register.module.edit');
Route::delete('/module/destroy/{modulecode}', [ModuleController::class, 'destroymodule'])->name('module.destroy');
Route::put('/module/update/{modulecode}', [ModuleController::class, 'updatemodule'])->name('module.update');
Route::get('/module/search', [ModuleController::class, 'search'])->name('module.search');

//departments
Route::get('register/department', [DepartmentController::class, 'registerDepartment'])->name('register.department');
Route::post('register/department/save', [DepartmentController::class, 'storeDepartment'])->name('department.store'); 
Route::get('register/department/form', [DepartmentController::class, 'departmentRegForm'])->name('register.department.form');
Route::get('register/department/edit/{deptcode}', [DepartmentController::class, 'departmentEditForm'])->name('register.department.edit');
Route::delete('/department/destroy/{deptcode}', [DepartmentController::class, 'destroydepartment'])->name('department.destroy');
Route::put('/department/update/{deptcode}', [DepartmentController::class, 'updatedepartment'])->name('department.update');
Route::get('/departments/search', [DepartmentController::class, 'search'])->name('department.search');

//profile
Route::get('profile/photo', [AdminController::class, 'fullProfile'])->name('admin.profile');
Route::get('profile/photo/edit', [AdminController::class, 'profileUpdate'])->name('admin.profile.edit');
Route::put('/profile/photo/store',[AdminController::class, 'storeProfile'] )->name('admin.store.profile');
Route::put('/Profile/save/{username}', [AdminController::class, 'updateAdmin'])->name('admin.profile.update');

//Results
Route::post('/results/publish', [ResultController::class,'publish'])->name('result.publish');
Route::post('/results/unpublish',[ResultController::class,'unpublish'])->name('result.unpublish');
Route::get('/students/results',[ResultController::class,'index'])->name('student.results');
Route::get('/students/results/search',[ResultController::class,'search'])->name('result.search');
Route::get('/students/results/{studentID}',[ResultController::class,'showResult'])->name('student.result.view');
//academic year
Route::put('/academic-year/update', [AcademicYearController::class, 'update'])->name('academic-year.update');
Route::get('/academic_years/show', [AcademicYearController::class,'index'])->name('academic_year.index');
Route::get('/academic_years/create', [AcademicYearController::class,'create'])->name('academic_year.create');
Route::post('/academic_years/create', [AcademicYearController::class,'store'])->name('academic_year.store');
//enrollment
Route::post('/enrollment/enroll', [EnrollmentController::class, 'enroll'])->name('enrollment.enroll');
Route::get('/students/enrollments/search',[EnrollmentController::class,'search'])->name('enrollment.search');
Route::get('/students/enrollments',[EnrollmentController::class,'index'])->name('enrollment.view');
});

/*************************** End Admin routes ****Admin
/*************************** Lecturer routes ***************/
Route::prefix('lecturer')->middleware('guest')->group(function(){
Route::get('/login',[LecturerController::class,'index'])->name('lecturer.login.form');
Route::post('/login/owner',[LecturerController::class,'login'])->name('lecturer.login');

Route::get('forgot-password', [LecturerController::class, 'create'])->name('lecturer.password.request');
Route::post('forgot-password', [LecturerController::class, 'store'])->name('lecturer.password.email');
Route::get('reset-password/{token}', [LecturerController::class, 'createReset'])->name('lecturer.password.reset');
Route::post('reset-password', [LecturerController::class, 'storeReset'])->name('lecturer.password.store');
});

Route::prefix('lecturer')->middleware('lecturer')->group(function(){
Route::get('/dashboard',[LecturerController::class,'dashboard'])->name('lecturer.dashboard');
Route::post('/logout',[LecturerController::class,'destroy'])->name('lecturer.logout');

//password
Route::put('/password/update', [LecturerController::class, 'update'])->name('lecturer.password.update');
Route::get('/password', [LecturerController::class, 'changePassword'])->name('lecturer.changePassword');

//results 
Route::get('/results/{moduleCode}/{studentID}/{moduleName}/edit', [ResultController::class,'edit'])->name('result.edit');
Route::put('/results/update/{moduleName}', [ResultController::class,'update'])->name('result.update');
Route::get('/results/upload', [ResultController::class, 'resultsUpload'])->name('lecturer.results.upload');
Route::get('/results', [ResultController::class, 'resultsView'])->name('lecturer.results');
Route::get('/results/upload/{moduleCode}/{moduleName}', [ResultController::class, 'resultsAdd'])->name('lecturer.module.addresult');
Route::post('/results/upload/new/{moduleCode}', [ResultController::class, 'newResult'])->name('lecturer.module.newResult');
Route::get('/results/students/{moduleCode}/{moduleName}', [ResultController::class, 'viewStudents'])->name('lecturer.module.viewStudents');
Route::get('/results/students/search/{moduleCode}/{moduleName}', [ResultController::class, 'searchStudents'])->name('lecturer.module.searchStudents');
Route::get('/results/students/module/{moduleCode}/{moduleName}', [ResultController::class, 'viewStudentsResults'])->name('lecturer.module.viewStudentsResults');
Route::get('/results/students/search/students/{moduleCode}/{moduleName}', [ResultController::class, 'searchStudentsResults'])->name('lecturer.results.searchResults');
Route::delete('/results/destroy/{moduleCode}/{studentID}', [ResultController::class, 'destroyresult'])->name('result.destroy');

// module view
Route::get('/module/view', [ResultController::class, 'resultsView'])->name('lecturer.module.view');
Route::get('/module/search', [ModuleController::class, 'lecturerSearchModule'])->name('lecturer.module.search');
Route::get('/module/search/view', [ModuleController::class, 'lecturerSearchModuleView'])->name('lecturer.modules.view');
Route::get('/module/view/modules', [ModuleController::class, 'lecturerModuleResults'])->name('lecturer.module.viewResults');

// import results via excel
Route::post('/results/upload/excel/{moduleCode}', [ResultController::class, 'excelUpload'])->name('lecturer.results.upload.excel');


//profile
Route::get('profile/photo', [LecturerController::class, 'fullProfile'])->name('lecturer.profile');
Route::get('profile/photo/edit', [LecturerController::class, 'profileUpdate'])->name('lecturer.profile.edit');
Route::put('/profile/photo/store',[LecturerController::class, 'storeProfile'] )->name('lecturer.store.profile');


}); 
    /*************************** End Lecturer routes ***************/


require __DIR__.'/auth.php';