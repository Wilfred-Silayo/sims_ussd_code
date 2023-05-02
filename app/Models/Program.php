<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Program extends Model
{
    use HasFactory;
    protected $primaryKey = 'programID';
    public $incrementing = false;
    protected $fillable=['programID','programname','ntalevel','capacity','department'];

    public function students()
    {
        return $this->hasMany(Student::class, 'programID', 'program');
    }
    
}
