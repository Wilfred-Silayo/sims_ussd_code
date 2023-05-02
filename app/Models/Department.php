<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Module;
use App\Models\Lecturer;

class Department extends Model
{
    use HasFactory;
    protected $primaryKey = 'deptcode';
    public $incrementing = false;
    protected $fillable=['deptcode','deptname'];

    public function module(){
        return $this->hasMany(Module::class);
    }
    public function lecturer(){
        return $this->hasMany(Lecturer::class);
    }
}
