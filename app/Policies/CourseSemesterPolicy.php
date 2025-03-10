<?php

namespace App\Policies;

use App\Models\User;

class CourseSemesterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('access course semester');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage course semesters');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage course semesters');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {

        return $user->hasPermissionTo('manage course semesters');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('manage course semesters');
    }
}
