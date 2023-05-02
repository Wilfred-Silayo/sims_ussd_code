<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Module;
use App\Models\Department;

class Lecturer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guard = 'lecturer';
    protected $primaryKey = 'username';
    public $incrementing = false;
     
    protected $fillable = [
        'username','admission', 'password','firstname','lastname','middlename','email','gender','phone','dob',
        'nationality','maritalstatus','department','profile_photo_path',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function module(){
        return $this->hasMany(Module::class);
    }

    public function department(){
        return $this->belongsTo(Department::class,'department','deptcode')->withDefault();
    }
    
}
