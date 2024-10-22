<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBatch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'academic_year', 'status'];

    // A batch can have many students
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // A batch can have multiple modules
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
