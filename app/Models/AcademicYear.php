<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable=['year','semester','current'];

    protected $casts = [
        'current' => 'boolean',
    ];
    
    public function modules()
    {
    return $this->hasMany(Module::class);
    }

}
