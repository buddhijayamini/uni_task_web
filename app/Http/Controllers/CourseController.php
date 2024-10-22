<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest; // Assuming you have a request for validation
use App\Repositories\CourseRepository; // Your repository for handling course logic
use App\Models\Course;
use App\Models\Department;
use App\Models\Faculty;
use App\Services\CourseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    protected $courseRepository;
    protected $courseService;

    public function __construct(CourseRepository $courseRepository, CourseService $courseService)
    {
        $this->courseRepository = $courseRepository;
        $this->courseService = $courseService;

        // Apply authorization middleware for permissions
        $this->middleware('permission:manage courses', ['except' => ['index','show']]);
        $this->middleware('permission:access course', ['only' => ['index', 'show']]);
    }

    // Display a listing of courses
    public function index()
    {
        // Check if the user has the necessary permissions or roles
        $this->authorize('viewAny', Course::class);

        // Fetch all courses based on user permissions
        $courses = $this->courseRepository->getAll(); // Fetch all courses
        return view('courses.index', compact('courses'));
    }

    public function getDepartments($facultyId)
    {
        $this->authorize('view', Course::class);

        // Fetch departments related to the selected faculty
        $departments = Department::where('faculty_id', $facultyId)->get();

        return response()->json($departments); // Return the departments as JSON
    }


    // Show the form for creating a new course
    public function create()
    {
        $this->authorize('create', Course::class);  // Check permission to create courses

        $faculties = Faculty::all(); // Fetch all faculties
        $categories = Department::all(); // Fetch all categories (ensure you have a Category model)

        return view('courses.create', compact(['faculties', 'categories']));
    }

    // Store a newly created course
    public function store(CourseRequest $request)
    {
        $this->authorize('create', Course::class);  // Check permission to create courses

        DB::beginTransaction();
        try {
            $this->courseRepository->create($request->validated()); // Use validated data
            DB::commit();

            return redirect()->route('courses.index')->with('success', 'Course created successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating course: ' . $e->getMessage());
            return back()->withErrors('Could not create the course. Please try again.');
        }
    }

    // Show the form for editing the specified course
    public function edit($id)
    {
        $this->authorize('update', Course::class);  // Check permission to update courses
        $faculties = Faculty::all(); // Fetch all faculties
        $categories = Department::all(); // Fetch all categories (ensure you have a Category model)

        $course = $this->courseRepository->findById($id);
        return view('courses.edit', compact(['course', 'faculties', 'categories']));
    }

    // Update the specified course
    public function update(CourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        $this->authorize('update', $course);  // Check permission to manage courses

        // Get the current authenticated user
        $user = Auth::user();

        // Use CourseService to update the course
        $result = $this->courseService->updateCourse($id, $request->validated(), $user);

        if ($result['success']) {
            return redirect()->route('courses.index')->with('success', $result['message']);
        } else {
            return back()->withErrors($result['message']);
        }
    }

    // Remove the specified course
    public function destroy($id)
    {
        $this->authorize('delete', Course::class);   // Check permission to delete courses

        DB::beginTransaction();
        try {
            $this->courseRepository->delete($id);
            DB::commit();

            return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting course: ' . $e->getMessage());
            return back()->withErrors('Could not delete the course. Please try again.');
        }
    }
}
