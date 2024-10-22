<?php

namespace App\Policies;

use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('access course');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage courses');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage courses');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($course): bool
    {
         $user = auth()->user();
        // // If the course is a draft, allow academic head to update
        // if ($course->status === 'draft') {
        //     return true;
        // }

        // // If course is published, only allow updates within 6 hours for non-admins
        // if ($course->status === 'publish' && $course->created_at) {
        //     $timeDiff = now()->diffInHours($course->created_at);

        //     if ($timeDiff > 6 && !$user->hasRole('Admin')) {
        //         return false;  // Deny update after 6 hours for non-admins
        //     }
        // }

        // // Allow admin to always update
        // return $user->hasRole('Admin');
        return $user->hasPermissionTo('manage courses') || $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('manage courses') || $user->hasRole('Admin');
    }
}
