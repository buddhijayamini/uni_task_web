<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function updateCourse($id, $validatedData, $user)
    {
        $course = Course::findOrFail($id);

        // Check if the course is published
        $isPublished = $course->status == 'publish';

        // Calculate the time difference for published courses (6-hour window)
        if ($isPublished) {
            $publishedAt = Carbon::parse($course->published_at);

            // Add 6 hours to the published_at timestamp
            $editDeadline = $publishedAt->addHours(6);
        }

        DB::beginTransaction();
        try {
            // Admin can update anytime
            if ($user->hasRole('Admin')) {
                $this->courseRepository->update($id, $validatedData);
            }
            // Academic Head can only update if status is draft or published within the 6-hour window
            elseif ($user->hasRole('Academic Head')) {
                if ($course->status == 'draft' || !$isPublished || now()->lessThanOrEqualTo($editDeadline)) {
                    $this->courseRepository->update($id, $validatedData);
                } else {
                    abort(403, 'You can only update this course if it is in draft status or within 6 hours of publication.');
                }
            } else {
                abort(403, 'You do not have permission to update this course.');
            }

            DB::commit();

            return ['success' => true, 'message' => 'Course updated successfully!'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating course: ' . $e->getMessage());

            return ['success' => false, 'message' => 'Could not update the course. Please try again.'];
        }
    }
}
