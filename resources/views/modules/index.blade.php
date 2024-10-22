@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Modules for Course: {{ $course->name }}</h1>

        <!-- Check if the user has permission to manage modules -->
        @can('manage modules')
            <a href="{{ route('modules.create', $course->id) }}" class="btn btn-primary mb-3">Create New Module</a>
        @endcan

        <div class="card">
            <div class="card-header">
                Module List
            </div>
            <div class="card-body">
                @if ($modules->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        No modules available for this course.
                    </div>
                @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Module Code</th>
                                <th>Module Name</th>
                                @can('manage modules')
                                    <th>Status</th>
                                    <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $module->code }}</td>
                                    <td>{{ $module->name }}</td>
                                    @can('manage modules')
                                        <td>{{ $module->status }}</td>
                                        <td>
                                            <a href="{{ route('modules.edit', [$course->id, $module->id]) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('modules.destroy', [$course->id, $module->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this module?');">Delete</button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
