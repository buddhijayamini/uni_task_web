<?php

namespace App\Repositories;

use App\Models\Module;
use Carbon\Carbon;

class ModuleRepository implements RepositoryInterface
{
    public function getAll()
    {
        return Module::all();
    }

    public function findById($id)
    {
        return Module::findOrFail($id);
    }

    public function findByIdList($id)
    {
        // Get the current authenticated user
        $user = auth()->user();

        // Build the base query to retrieve modules by course_id
        $query = Module::where('course_id', $id);

        // Exclude 'draft' status for specific roles
        if ($user && ($user->hasRole('Student') || $user->hasRole('Teacher'))) {
            $query->where('status', '!=', 'draft');
        }

        // Get the filtered modules
        return $query->get();
    }

    public function create(array $data)
    {
        return Module::create($data);
    }

    public function update($id, array $data)
    {
        $module = $this->findById($id);

        if ($data['status'] == 'publish') {
            $module->published_at = Carbon::now();
        }

        $module->update($data);
        return $module;
    }

    public function delete($id)
    {
        $module = $this->findById($id);
        $module->delete();
    }
}
