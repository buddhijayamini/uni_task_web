@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Courses</h1>
        @can('manage courses')
            <a href="{{ route('courses.create') }}" class="btn btn-primary">Add Course</a>
        @endcan
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Faculty</th>
                    <th>Category</th>
                    @can('manage courses')
                        <th>Status</th>
                    @endcan
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr>
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->faculty->name }}</td>
                        <td>{{ $course->department->name }}</td>
                        @can('manage courses')
                            <td>{{ $course->status }}</td>
                        @endcan
                        <td>
                            @can('manage courses')
                                <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Delete</button>
                                </form>
                            @endcan
                            @can('access module')
                                <a href="{{ route('modules.index', $course->id) }}" class="btn btn-info">View Modules</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
