@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Module: {{ $module->course->name }}</h1>

        <!-- Display any validation errors -->
        @if ($errors->any())
            <ul class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <!-- Update form -->
        <form action="{{ route('modules.update', ['courseId' => $courseId, 'moduleId' => $module->id]) }}" method="POST">
            @csrf
            @method('PUT') <!-- Use PUT method for updating -->

            <!-- Module Code -->
            <div class="form-group">
                <label for="code">Module Code</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $module->code) }}">
            </div>

            <!-- Module Name -->
            <div class="form-group">
                <label for="name">Module Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $module->name) }}">
            </div>

            {{-- <!-- Semester -->
            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="number" name="semester" class="form-control" value="{{ old('semester', $module->semester) }}">
            </div>

            <!-- Credit -->
            <div class="form-group">
                <label for="credit">Credit</label>
                <input type="number" name="credit" class="form-control" value="{{ old('credit', $module->credit) }}">
            </div>

            <!-- Type -->
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" class="form-control">
                    <option value="mandatory" {{ $module->type == 'mandatory' ? 'selected' : '' }}>Mandatory</option>
                    <option value="elective" {{ $module->type == 'elective' ? 'selected' : '' }}>Elective</option>
                </select>
            </div> --}}

            <!-- Status -->
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="draft" {{ $module->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="publish" {{ $module->status == 'publish' ? 'selected' : '' }}>Publish</option>
                </select>
            </div>

            <!-- Update button -->
            <button type="submit" class="btn btn-primary">Update Module</button>
        </form>
    </div>
@endsection
