<?php

namespace App\Http\Controllers;

use App\Models\BatchStudent;
use App\Models\CourseSemester;
use App\Models\Course;
use App\Models\Module;
use App\Models\Semester;
use App\Models\StudentBatch;
use App\Repositories\CourseSemesterRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseSemesterController extends Controller
{
    protected $courseSemesterRepository;

    public function __construct(CourseSemesterRepository $courseSemesterRepository)
    {
        $this->courseSemesterRepository = $courseSemesterRepository;

        // Apply authorization middleware for permission
        $this->middleware('permission:manage course semesters', ['except' => ['index', 'show', 'viewStudentSemester', 'getModuleSemester', 'storeStudentSemester', 'createStudentSemester']]);
        $this->middleware('permission:access course semester', ['only' => ['index', 'show', 'viewStudentSemester', 'getModuleSemester', 'storeStudentSemester', 'createStudentSemester']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', CourseSemester::class);

        // Fetch all CourseSemester based on user permissions
        $courseSemesters = $this->courseSemesterRepository->getAll(); // Fetch all CourseSemester
        return view('course_semester.index', compact('courseSemesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', CourseSemester::class);  // Check permission to create CourseSemester

        $batches = StudentBatch::all(); // Fetch all batches
        $courses = Course::where('status', 'publish')->get(); // Fetch all courses
        $semesters = Semester::all(); // Fetch all semester
        $modules = Module::where('status', 'publish')->get(); // Fetch all modules

        return view('course_semester.create', compact(['batches', 'courses', 'semesters', 'modules']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', CourseSemester::class);  // Check permission to create CourseSemester

        // Add validation here
        $validatedData = $request->validate([
            'semester_id' => 'required|integer',
            'module_id' => 'required|integer',
            'course_id' => 'required|integer',
            'credit' => 'required|integer',
            'type' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $this->courseSemesterRepository->create($validatedData); // Use validated data
            DB::commit();

            return redirect()->route('course_semester')->with('success', 'CourseSemester created successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating CourseSemester: ' . $e->getMessage());
            return back()->withErrors('Could not create the CourseSemester. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('update', CourseSemester::class);  // Check permission to update CourseSemester

        $batches = StudentBatch::all(); // Fetch all batches
        $courses = Course::where('status', 'publish')->get(); // Fetch all courses
        $semesters = Semester::all(); // Fetch all semester
        $modules = Module::where('status', 'publish')->get(); // Fetch all modules

        $courseSemester = $this->courseSemesterRepository->findById($id);
        return view('course_semester.edit', compact(['courseSemester', 'batches', 'courses', 'semesters', 'modules']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', CourseSemester::class);  // Check permission to manage courses

        DB::beginTransaction();
        try {
            $this->courseSemesterRepository->update($id, $request->all());
            DB::commit();

            return redirect()->route('course_semester')->with('success', 'CourseSemester updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update CourseSemester: ' . $e->getMessage());
            return back()->withErrors('Could not update the CourseSemester. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', CourseSemester::class);   // Check permission to delete CourseSemester

        DB::beginTransaction();
        try {
            $this->courseSemesterRepository->delete($id);
            DB::commit();

            return redirect()->route('course_semester.index')->with('success', 'CourseSemester deleted successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting CourseSemester: ' . $e->getMessage());
            return back()->withErrors('Could not delete the CourseSemester. Please try again.');
        }
    }

    public function getModules($courseId)
    {
        $this->authorize('view', CourseSemester::class);
        $modules = Module::where('course_id', $courseId)->get();
        return response()->json(['modules' => $modules]);
    }

    public function getModuleSemester($courseId, $semesterId)
    {
        $this->authorize('viewAny', CourseSemester::class);

        // Get course semesters that match the selected course and semester
        $courseSemesters = CourseSemester::where('course_id', $courseId)
            ->where('semester_id', $semesterId)
            ->get();

        Log::info("courseSemesters: $courseSemesters");

        // Extract related modules from the course semesters
        $modules = [];
        foreach ($courseSemesters as $courseSemester) {
            if ($courseSemester->module) {
                $modules[] = [
                    'id' => $courseSemester->module->id,
                    'name' => $courseSemester->module->name,
                    'type' => $courseSemester->type // Optional: to differentiate elective/mandatory
                ];
            }
        }

        return response()->json(['modules' => $modules]);
    }


    public function viewStudentSemester()
    {
        // Authorization check for viewing CourseSemesters
        $this->authorize('viewAny', CourseSemester::class);

        // Fetching all academic batches, courses, and semesters for the view
        $academicBatches = StudentBatch::all();
        $courses = Course::where('status', 'publish')->get(); // Fetch all published courses
        $semesters = Semester::all();

        // Fetch all student batch records related to the logged-in student
        $studentSemesters = BatchStudent::with(['batch'])->where('student_id', Auth::user()->id)->get();

        // Initialize an empty collection to store the final list of modules across all courses and semesters
        $finalModuleList = collect();

        // Loop through each student batch entry
        foreach ($studentSemesters as $studentSemester) {
            // Fetch the course_semester_id for the current student batch entry
            $courseSemesterId = $studentSemester->course_semester_id;

            // Fetch the course_id and semester_id based on the course_semester_id
            $courseSemester = CourseSemester::where('id', $courseSemesterId)->first(['course_id', 'semester_id']);

            if ($courseSemester) {
                // Fetch all mandatory (non-elective) modules for this course and semester
                $mandatoryModules = CourseSemester::where('course_id', $courseSemester->course_id)
                    ->where('semester_id', $courseSemester->semester_id)
                    ->where('type', 'mandatory') // Filter only mandatory modules
                    ->get();

                // Fetch elective modules selected by the student for this course and semester
                $selectedElectiveModules = CourseSemester::whereIn('id', $studentSemesters->pluck('course_semester_id'))
                    ->where('type', 'elective') // Filter only elective modules
                    ->get();

                // Merge the mandatory and elective modules for this course and semester
                $finalModuleList = $finalModuleList->merge($mandatoryModules)->merge($selectedElectiveModules);
            }
        }
        // Remove duplicate modules by their ID
        $finalModuleList = $finalModuleList->unique('id');

        // Pass all relevant data to the view
        return view('student_course.index', compact(['studentSemesters', 'finalModuleList', 'academicBatches', 'courses', 'semesters']));
    }


    public function createStudentSemester()
    {
        $this->authorize('viewAny', CourseSemester::class);

        $academicBatches = StudentBatch::all();
        $courses = Course::where('status', 'publish')->get(); // Fetch all courses
        $semesters = Semester::all();
        $limits = 0;
        
        foreach($semesters  as $semester){
            $limit = Semester::find($semester->id);
            $limits = $limit->elective_credits_limit;
        }

        // Fetch all CourseSemester based on user permissions
        $courseSemesters = $this->courseSemesterRepository->getAll(); // Fetch all CourseSemester
        return view('student_course.create', compact(['courseSemesters', 'academicBatches', 'limits', 'courses', 'semesters']));
    }

    public function storeStudentSemester(Request $request)
    {
        // Validate form input
        $request->validate([
            'batch_id' => 'required|exists:student_batches,id',
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'elective_modules' => 'nullable|array'
        ]);

        // Fetch the selected semester's elective_credits_limit
        $semester = Semester::findOrFail($request->semester_id);
        $electiveCreditsLimit = $semester->elective_credits_limit;

        // Count the number of selected elective modules
        $selectedElectiveModules = $request->input('elective_modules', []);
        $selectedElectiveCount = count($selectedElectiveModules);

        // Check if the selected electives match the semester's elective_credits_limit
        if ($selectedElectiveCount != $electiveCreditsLimit) {
            return redirect()->back()->withErrors([
                'elective_modules' => "You must select exactly {$electiveCreditsLimit} elective modules."
            ])->withInput();
        }

        // Loop through each selected elective module and save it inside BatchStudent
        foreach ($selectedElectiveModules as $moduleId) {
            // Find the corresponding CourseSemester record for the selected module
            $courseSemester = CourseSemester::where('course_id', $request->course_id)
                ->where('semester_id', $request->semester_id)
                ->where('module_id', $moduleId)
                ->first();

            if ($courseSemester) {
                // Save the selected course semester info into BatchStudent
                $batchStudent = new BatchStudent();
                $batchStudent->batch_id = $request->batch_id;
                $batchStudent->student_id = Auth::user()->id;
                $batchStudent->course_semester_id = $courseSemester->id; // Save course semester ID
                $batchStudent->save();
            }
        }


        return redirect()->route('course_semester.student')->with('success', 'Course Semester added successfully.');
    }
}
