<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Program;
class Module extends Model
{
    use HasFactory;
    protected $primaryKey = 'modulecode';
    public $incrementing = false;
    protected $fillable=['modulecode','modulename','credit','elective','program','semester','department','lectureID'];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }
    public function program(){
        return $this->belongsTo(Program::class,'program', 'programID');
    }
    public function enrollments()
    {
    return $this->hasMany(Enrollment::class);
    }

}

