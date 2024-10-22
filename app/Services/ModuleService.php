<?php

namespace App\Services;

use App\Models\Module;
use App\Repositories\ModuleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ModuleService
{
    protected $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function updateModule($id, $validatedData, $user)
    {
        $module = Module::findOrFail($id);

        // Check if the course is published
        $isPublished = $module->status == 'publish';

        // Calculate the time difference for published courses (6-hour window)
        if ($isPublished) {
            $publishedAt = Carbon::parse($module->published_at);

            // Add 6 hours to the published_at timestamp
            $editDeadline = $publishedAt->addHours(6);
        }

        DB::beginTransaction();
        try {
            // Admin can update anytime
            if ($user->hasRole('Admin')) {
                $this->moduleRepository->update($id, $validatedData);
            }
            // Academic Head can only update if status is draft or published within the 6-hour window
            elseif ($user->hasRole('Academic Head')) {
                if ($module->status == 'draft' || !$isPublished || now()->lessThanOrEqualTo($editDeadline)) {
                    $this->moduleRepository->update($id, $validatedData);
                } else {
                    abort(403, 'You can only update this module if it is in draft status or within 6 hours of publication.');
                }
            } else {
                abort(403, 'You do not have permission to update this module.');
            }

            DB::commit();

            return ['success' => true, 'message' => 'module updated successfully!'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating module: ' . $e->getMessage());

            return ['success' => false, 'message' => 'Could not update the module. Please try again.'];
        }
    }
}
