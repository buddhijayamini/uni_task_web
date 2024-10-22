<?php

namespace App\Repositories;

use App\Models\CourseSemester;
use Illuminate\Database\Eloquent\Collection;

class CourseSemesterRepository implements RepositoryInterface
{
    /**
     * Get all CourseSemester.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return CourseSemester::with(['module', 'course', 'semester'])->get();
    }

    /**
     * Create a new CourseSemester.
     *
     * @param array $data
     * @return CourseSemester
     */
    public function create(array $data): CourseSemester
    {
        return CourseSemester::create($data);
    }

    /**
     * Find a CourseSemester by ID.
     *
     * @param int $id
     * @return CourseSemester
     */
    public function findById($id): CourseSemester
    {
        return CourseSemester::with(['module', 'course', 'semester'])->findOrFail($id);
    }

    public function findByIdList($id)
    {
        return null;
    }

    /**
     * Update a CourseSemester.
     *
     * @param int $id
     * @param array $data
     * @return CourseSemester
     */
    public function update($id, array $data): CourseSemester
    {
        $CourseSemester = $this->findById($id);

        $CourseSemester->update($data);

        return $CourseSemester;
    }

    /**
     * Delete a CourseSemester.
     *
     * @param int $id
     * @return bool|null
     */
    public function delete($id): ?bool
    {
        $CourseSemester = $this->findById($id);
        return $CourseSemester->delete();
    }
}
