@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Module</h2>
        @if ($errors->any())
            <ul class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="{{ route('modules.store', $courseId) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="code">Module Code</label>
                <input type="text" name="code" readonly class="form-control" value="{{ $newModuleCode }}">
            </div>

            <div class="form-group">
                <label for="name">Module Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="draft">Draft</option>
                    <option value="publish">Publish</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Create Module</button>
        </form>
    </div>
@endsection
