<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Course;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use App\Services\ModuleService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    protected $moduleRepository;
    protected $moduleService;

    public function __construct(ModuleRepository $moduleRepository, ModuleService $moduleService)
    {
        $this->moduleRepository = $moduleRepository;
        $this->moduleService = $moduleService;

        // Add permission middleware
        $this->middleware('permission:manage modules', ['except' => ['index','show']]);
        $this->middleware('permission:access module', ['only' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index($courseId)
    {
        // Authorize view action
        $this->authorize('viewAny', Module::class);

        // Fetch the course name
        $course = Course::findOrFail($courseId);
        $modules = $this->moduleRepository->findByIdList($courseId);

        return view('modules.index', compact('modules', 'course', 'courseId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($courseId)
    {
        // Authorize create action
        $this->authorize('create', Module::class);

        // Fetch the last module entry in the database
        $lastModule = Module::orderBy('id', 'desc')->first();

        // Default prefix for the module code
        $prefix = 'MD';

        if ($lastModule) {
            // Extract the numeric part from the last module code
            $lastModuleCode = $lastModule->code;
            $lastNumber = intval(substr($lastModuleCode, 2)); // Get numeric part, e.g., '001'

            // Increment the number
            $newNumber = $lastNumber + 1;
        } else {
            // If no module exists, start from '001'
            $newNumber = 1; // Start with number 1 if no record exists
        }

        // Ensure the new number is always 3 digits (e.g., '001', '002')
        $newNumberPadded = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Create the new module code
        $newModuleCode = $prefix . $newNumberPadded;

        // Pass the courseId and the new module code to the view
        return view('modules.create', compact('courseId', 'newModuleCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuleRequest $request, $courseId)
    {
        // Authorize create action
        $this->authorize('create', Module::class);

        DB::beginTransaction();
        try {
            // Add course_id to the validated data
            $data = $request->validated();
            $data['course_id'] = $courseId;
            $this->moduleRepository->create($data);
            DB::commit();

            return redirect()->route('modules.index', $courseId)->with('success', 'Module created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating Module: ' . $e->getMessage());
            return back()->withErrors('Could not create the Module. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($courseId, $moduleId)
    {
        // Authorize view action
        $this->authorize('view', Module::class);

        // Fetch the course name
        $course = Course::findOrFail($courseId);
        // Fetch the module
        $module = $this->moduleRepository->findById($moduleId);

        // Show the module view
        return view('modules.show', compact('module', 'course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($courseId, $moduleId)
    {
        // Authorize update action
        $this->authorize('update', Module::class);
        $module = $this->moduleRepository->findById($moduleId);

        return view('modules.edit', compact('module', 'courseId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuleRequest $request, $courseId, $moduleId)
    {
        // Authorize update action
        $module = $this->moduleRepository->findById($moduleId);
        $this->authorize('update', $module);

        // Get the current authenticated user
        $user = Auth::user();

        // Use CourseService to update the course
        $result = $this->moduleService->updateModule($moduleId, $request->validated(), $user);
        if ($result['success']) {
            return redirect()->route('modules.index', $courseId)
                ->with('success', 'Module updated successfully.');
        } else {
            return back()->withErrors($result['message']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId, $moduleId)
    {
        // Authorize delete action
        $module = $this->moduleRepository->findById($moduleId);
        $this->authorize('delete', $module);

        $this->moduleRepository->delete($moduleId);
        return redirect()->route('modules.index', $courseId)->with('success', 'Module deleted successfully.');
    }
}
