<?php

namespace App\Services;

use App\Models\BatchStudent;
use App\Models\CourseSemester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception; // Import the Exception class

class CreditCalculatorService
{
    public function calculateCredits($courseId, $semesterId, $batchId)
    {
        try {
            // Retrieve all student batches for the logged-in user in the specified batch
            $studentBatches = BatchStudent::where('student_id', Auth::user()->id)
                ->where('batch_id', $batchId)
                ->get();

            // If there are no student batches, return zero credits
            if ($studentBatches->isEmpty()) {
                return [
                    'mandatory_credits' => 0,
                    'elective_count' => 0,
                    'total_credits' => 0,
                ];
            }

            // Fetch all modules for the given course and semester
            $modules = CourseSemester::where('course_id', $courseId)
                ->where('semester_id', $semesterId)
                ->get();

            // Initialize counters
            $mandatoryCredits = 0;
            $electiveCredits = 0; // To count total credits from elective modules
            $electiveCount = 0; // Count of enrolled elective modules

            // Loop through each module to calculate mandatory and elective credits
            foreach ($modules as $module) {
                foreach ($studentBatches as $studentBatch) {
                    // Check if the student is enrolled in this mandatory module
                    if ($module->type === 'mandatory') {
                        $mandatoryCredits += $module->credit; // Accumulate mandatory credits
                    }

                    // Check if the student is enrolled in this elective module
                    if ($module->type === 'elective' && $studentBatch->course_semester_id === $module->id) {
                        $electiveCredits += $module->credit; // Increment elective credits by the module's credit value + 1
                        $electiveCount += 1; // Increment the elective count
                    }
                }
            }

            Log::info("Mandatory Credits: $mandatoryCredits");
            Log::info("Elective Credits: $electiveCredits");
            Log::info("Elective Count: $electiveCount");

            // Calculate total credits as the sum of mandatory credits and elective credits
            $totalCredits = $mandatoryCredits + $electiveCredits; // Total credits

            return [
                'mandatory_credits' => $mandatoryCredits,
                'elective_credits' => $electiveCredits,
                'elective_count' => $electiveCount, // Return the count of enrolled electives
                'total_credits' => $totalCredits, // Return total credits
            ];
        } catch (Exception $e) {
            // Log the exception message and stack trace
            Log::error('Error calculating credits: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Optionally, return a default response or rethrow the exception
            return [
                'mandatory_credits' => 0,
                'elective_count' => 0,
                'total_credits' => 0,
                'error' => 'An error occurred while calculating credits.'
            ];
        }
    }
}
