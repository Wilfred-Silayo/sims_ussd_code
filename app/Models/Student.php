<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'username';
    public $incrementing = false;
    
    protected $fillable = [
        'username','admission', 'password','firstname','lastname','middlename','dob','email','gender','phone',
        'nationality','maritalstatus','program','yearofstudy','profile_photo_path',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token','active','status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthIdentifierName(){
        return 'username';
    }
    public function academicYears()
    {
    return $this->hasMany(AcademicYear::class);
    }

    public function program()
    {
    return $this->belongsTo(Program::class, 'program', 'programID');
    }


}
