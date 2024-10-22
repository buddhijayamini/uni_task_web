<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Semester;
use App\Services\CreditCalculatorService;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    protected $creditCalculator;

    public function __construct(CreditCalculatorService $creditCalculator)
    {
        $this->creditCalculator = $creditCalculator;
        $this->middleware('permission:access course semester', ['only' => ['showCredits', 'viewCredits']]);
    }

    public function showCredits(Request $request, $courseId, $semester,  $batchId)
    {
        $credits = $this->creditCalculator->calculateCredits($courseId, $semester, $batchId);

        return response()->json($credits);
    }

    public function viewCredits($courseId, $semesterId, $batchId)
    {
        // Check if the course or semester exists
        if (!$courseId || !$semesterId || !$batchId) {
            abort(404); // Redirect to the 404 page if either is not found
        }

        $credits = $this->creditCalculator->calculateCredits($courseId, $semesterId, $batchId);
        $course = Course::find($courseId);
        $semester = Semester::find($semesterId);

        // Return the view with the credits data
        return view('credits.view', compact('credits', 'course', 'semester'));
    }
}
