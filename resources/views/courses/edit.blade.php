@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Course</h1>
        @if ($errors->any())
            <ul class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="{{ route('courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Course Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $course->name }}" required>
            </div>

            <div class="form-group">
                <label for="seo_url">SEO URL</label>
                <input type="text" name="seo_url" id="seo_url" class="form-control" value="{{ $course->seo_url }}" required>
            </div>

            <div class="form-group">
                <label for="faculty">Faculty</label>
                <select name="faculty_id" id="faculty-select" class="form-control" required>
                    <option value="">Select Faculty</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ $course->faculty->id === $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select name="category_id" id="category-select" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $course->department->id === $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="publish" {{ $course->status === 'publish' ? 'selected' : '' }}>Publish</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Fetch departments when the faculty is changed
            $('#faculty-select').on('change', function() {
                var facultyId = $(this).val();
                if (facultyId) {
                    $.ajax({
                        url: '/departments/' + facultyId, // Ensure this URL is correct
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#category-select').empty();
                            $('#category-select').append('<option value="">Select Category</option>');
                            $.each(data, function(key, value) {
                                $('#category-select').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            // Set selected category if it matches the course's category
                            $('#category-select').val('{{ $course->department->id }}'); // Ensure this matches the ID of the current course category
                        }
                    });
                } else {
                    $('#category-select').empty();
                    $('#category-select').append('<option value="">Select Category</option>');
                }
            });

            // Trigger change event on page load to set the correct category based on existing course
            $('#faculty-select').trigger('change');
        });
    </script>
@endsection
