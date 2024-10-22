<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchStudent extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'batch_id', 'course_semester_id'];

    //  user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // batch
    public function batch()
    {
        return $this->belongsTo(StudentBatch::class);
    }

    // If BatchStudent can have many CourseSemesters
    public function courseSemesters()
    {
        return $this->belongsToMany(CourseSemester::class,'id','course_semester_id');
    }
}
