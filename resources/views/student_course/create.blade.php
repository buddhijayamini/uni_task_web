@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add Student Course Semester</h1>
        @if ($errors->any())
            <ul class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form id="courseSemesterForm" action="{{ route('course_semester.student.store') }}" method="POST">
            @csrf

            <!-- Academic Batch Year -->
            <div class="form-group">
                <label for="academic_batch_year">Academic Batch Year</label>
                <select name="batch_id" id="academic_batch_year" class="form-control" required>
                    <option value="" disabled selected>Select an academic year</option>
                    @foreach ($academicBatches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->academic_year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Course Selection -->
            <div class="form-group">
                <label for="course">Course</label>
                <select name="course_id" id="course" class="form-control" required>
                    <option value="" disabled selected>Select a course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Semester Selection -->
            <div class="form-group">
                <label for="semester">Semester</label>
                <select name="semester_id" id="semester" class="form-control" required>
                    <option value="" disabled selected>Select a semester</option>
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Elective Modules -->
            <div class="form-group" id="modules-container" style="display: none;">
                <label for="non-elective_modules">Core Modules</label>
                <ul id="non-elective-module-list" class="list-group"></ul>
                <label for="elective_modules">Elective Modules (Select up to {{ $limits }})</label>
                <div id="elective-module-checkboxes"></div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const academicBatchYear = document.getElementById('academic_batch_year');
            const courseId = document.getElementById('course');
            const semesterId = document.getElementById('semester');
            const modulesContainer = document.getElementById('modules-container');
            const electiveModuleContainer = document.getElementById('elective-module-checkboxes');
            const nonElectiveModuleContainer = document.getElementById('non-elective-module-list');

            // Function to clear the modules container
            function clearModulesContainer() {
                electiveModuleContainer.innerHTML = '';
                nonElectiveModuleContainer.innerHTML = '';
                modulesContainer.style.display = 'none'; // Hide modules container
            }

            // Function to fetch and display modules when all fields are selected
            function fetchModules() {
                const courseValue = courseId.value;
                const semesterValue = semesterId.value;

                if (courseValue && semesterValue) {
                    fetch(`/course-semester/getModuleSemester/${courseValue}/${semesterValue}`)
                        .then(response => response.json())
                        .then(data => {
                            electiveModuleContainer.innerHTML = '';
                            nonElectiveModuleContainer.innerHTML = '';

                            data.modules.forEach(module => {
                                if (module.type === 'elective') {
                                    // Add checkbox for elective modules
                                    electiveModuleContainer.innerHTML += `
                                        <div class="form-check">
                                            <input class="form-check-input elective-checkbox" type="checkbox" name="elective_modules[]" value="${module.id}">
                                            <label class="form-check-label">${module.name}</label>
                                        </div>
                                    `;
                                } else {
                                    // Add list item for non-elective modules
                                    nonElectiveModuleContainer.innerHTML += `
                                        <li class="list-group-item">${module.name}</li>
                                    `;
                                }
                            });

                            // Show modules container if there are modules
                            if (data.modules.length > 0) {
                                modulesContainer.style.display = 'block';
                            }
                        });
                }
            }

            // Event listener for semester selection
            semesterId.addEventListener('change', function() {
                clearModulesContainer(); // Clear previous modules
                if (courseId.value && academicBatchYear.value) {
                    fetchModules(); // Fetch modules only if course and batch year are selected
                }
            });

            // Event listener for course selection
            courseId.addEventListener('change', function() {
                clearModulesContainer(); // Clear modules when course changes
            });

            // Event listener for academic batch year selection
            academicBatchYear.addEventListener('change', function() {
                clearModulesContainer(); // Clear modules when academic batch year changes
            });

            // Limit the elective module selection to 2
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('elective-checkbox')) {
                    const selectedElectives = document.querySelectorAll('.elective-checkbox:checked')
                    .length;
                    if (selectedElectives > 2) {
                        e.target.checked = false; // Uncheck the last checkbox
                        alert('You can select up to 2 elective modules only.');
                    }
                }
            });
        });
    </script>
@endsection
