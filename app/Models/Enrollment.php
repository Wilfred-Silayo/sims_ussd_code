<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Module;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollment';
    protected $primaryKey = ['studentID', 'moduleCode'];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'studentID',
        'moduleCode',
        'semester',
        'academicYear',
        'Coursework',
        'semesterExam',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentID', 'username');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'moduleCode', 'modulecode');
    }
}
