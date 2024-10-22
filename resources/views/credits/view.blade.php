@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Credits Summary for Course : {{ $course->name }} (Semester : {{ $semester->name }})</h1>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Total Mandatory Credits: {{ $credits['mandatory_credits'] }}</h5>
            <h5>Total Elective Modules: {{ $credits['elective_credits'] }}</h5>
            <h5>Total Elective Count: {{ $credits['elective_count'] }}</h5>
            <h5>Total Credits: {{ $credits['total_credits'] }}</h5>
        </div>
    </div>

    {{-- <a href="{{ url('/courses') }}" class="btn btn-primary mt-3">Back to Courses</a> --}}
</div>
@endsection
