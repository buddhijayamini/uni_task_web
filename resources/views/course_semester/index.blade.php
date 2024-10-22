@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Course Semesters</h1>
        @can('manage course semesters')
            <a href="{{ route('course_semester.create') }}" class="btn btn-primary">Add Course Semester</a>
        @endcan
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Module</th>
                    @can('manage course semesters')
                        <th>Credit</th>
                    @endcan
                    <th>Type</th>
                    @can('manage course semesters')
                        <th>Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach ($courseSemesters as $courseSemester)
                    <tr>
                        <td>{{ $courseSemester->id }}</td>
                        <td>{{ $courseSemester->course->name }}</td>
                        <td>{{ $courseSemester->semester->name }}</td>
                        <td>{{ $courseSemester->module->name }}</td>
                        @can('manage course semesters')
                            <td>{{ $courseSemester->credit }}</td>
                        @endcan
                        <td>{{ $courseSemester->type }}</td>
                        @can('manage course semesters')
                            <td>
                                <a href="{{ route('course_semester.edit', $courseSemester->id) }}"
                                    class="btn btn-warning">Edit</a>
                                <form action="{{ route('course_semester.destroy', $courseSemester->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
