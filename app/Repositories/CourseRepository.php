<?php

namespace App\Repositories;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements RepositoryInterface
{
    /**
     * Get all courses.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        // Get the current authenticated user
        $user = auth()->user();

        // Build the base query to load the related models
        $query = Course::with(['modules', 'faculty', 'department']);

        // Exclude 'draft' status for specific roles
        if ($user->hasRole('Student') || $user->hasRole('Teacher')) {
            $query->where('status', '!=', 'draft');
        }

        // Get the filtered courses
        return $query->get();
    }

    /**
     * Create a new course.
     *
     * @param array $data
     * @return Course
     */
    public function create(array $data): Course
    {
        return Course::create($data);
    }

    /**
     * Find a course by ID.
     *
     * @param int $id
     * @return Course
     */
    public function findById($id): Course
    {
        return Course::with('modules')->findOrFail($id);
    }

    public function findByIdList($id)
    {
        return null;
    }

    /**
     * Update a course.
     *
     * @param int $id
     * @param array $data
     * @return Course
     */
    public function update($id, array $data): Course
    {
        $course = $this->findById($id);

        if ($data['status'] == 'publish') {
            $course->published_at = Carbon::now();
        }

        $course->update($data);

        return $course;
    }

    /**
     * Delete a course.
     *
     * @param int $id
     * @return bool|null
     */
    public function delete($id): ?bool
    {
        $course = $this->findById($id);
        return $course->delete();
    }
}
