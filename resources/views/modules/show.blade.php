@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Module Details</h1>

        <div class="card">
            <div class="card-header">
                <h3>{{ $module->name }}</h3>
            </div>
            <div class="card-body">
                <p><strong>Course:</strong> {{ $course->name }}</p>
                <p><strong>Code:</strong> {{ $module->code }}</p>
                <p><strong>Name:</strong> {{ $module->name }}</p>
                <p><strong>Semester:</strong> {{ $module->semester }}</p>
                <p><strong>Credit:</strong> {{ $module->credit }}</p>
                <p><strong>Status:</strong> {{ $module->status }}</p>
                @can('manage module')
                    <p><strong>Module Description:</strong> {{ $module->description }}</p>
                    <p><strong>Created At:</strong> {{ $module->created_at->format('d M Y') }}</p>
                    <p><strong>Last Updated:</strong> {{ $module->updated_at->format('d M Y') }}</p>
                @endcan
            </div>
        </div>
    </div>
@endsection
