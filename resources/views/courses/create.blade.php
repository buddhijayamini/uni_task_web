@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <ul class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <h1>Create Course</h1>
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Course Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="seo_url">SEO URL</label>
                <input type="text" name="seo_url" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="faculty">Faculty</label>
                <select name="faculty_id" class="form-control" id="faculty-select" required>
                    <option value="">Select Faculty</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category_id" class="form-control" id="category-select" required>
                    <option value="">Select Category</option>
                    <!-- Categories will be loaded dynamically here -->
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control" required>
                    <option value="draft">Draft</option>
                    <option value="publish">Publish</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Create Course</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#faculty-select').on('change', function() {
                var facultyId = $(this).val();
                if (facultyId) {
                    $.ajax({
                        url: '/departments/' + facultyId, // Adjust the route as needed
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#category-select').empty();
                            $('#category-select').append(
                                '<option value="">Select Category</option>');
                            $.each(data, function(key, value) {
                                $('#category-select').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#category-select').empty();
                    $('#category-select').append('<option value="">Select Category</option>');
                }
            });
        });
    </script>

@endsection
