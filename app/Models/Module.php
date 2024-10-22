<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'course_id', 'semester', 'description', 'status', 'published_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
