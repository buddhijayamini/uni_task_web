@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Batch Semester</h2>
        @if ($errors->any())
            <ul class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="{{ route('course_semester.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="course">Course</label>
                <select name="course_id" id="course_id" class="form-control" required>
                    <option value="">Select Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="semester">Semester</label>
                <select name="semester_id" class="form-control" required>
                    <option value="">Select Semester</option>
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="module">Module</label>
                <select name="module_id" id="module_id" class="form-control" required>
                    <option value="">Select Module</option>
                </select>
            </div>

            <div class="form-group">
                <label for="credit">Credit</label>
                <input type="number" name="credit" class="form-control" value="{{ old('credit') }}">
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="mandatory" {{ old('type') == 'mandatory' ? 'selected' : '' }}>Mandatory</option>
                    <option value="elective" {{ old('type') == 'elective' ? 'selected' : '' }}>Elective</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('course_id').addEventListener('change', function() {
            var courseId = this.value;
            var moduleSelect = document.getElementById('module_id');
            moduleSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/courses/${courseId}/modules-list`)
                .then(response => response.json())
                .then(data => {
                    moduleSelect.innerHTML = '<option value="">Select Module</option>';
                    data.modules.forEach(module => {
                        var option = document.createElement('option');
                        option.value = module.id;
                        option.textContent = module.name;
                        moduleSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading modules:', error);
                    moduleSelect.innerHTML = '<option value="">Error loading modules</option>';
                });
        });
    </script>
@endsection
