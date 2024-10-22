<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'seo_url', 'faculty_id', 'category_id', 'status','published_at'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'category_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
