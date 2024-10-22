@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Student Course Semesters</h1>
        @can('access course semester')
            <a href="{{ route('course_semester.student.create') }}" class="btn btn-primary">Add Student Course Semester</a>
        @endcan
        </br></br>
        @php
            // Separate mandatory and elective modules
            $mandatoryModules = $finalModuleList->where('type', 'mandatory');
            $electiveModules = $finalModuleList->where('type', 'elective');

            // Merge mandatory modules with elective modules, ensuring mandatory come first
            $sortedModules = $mandatoryModules->merge($electiveModules);
        @endphp

        @foreach ($sortedModules->groupBy('course_id') as $courseId => $modules)
            @if ($modules->isNotEmpty())
                @php
                    // Retrieve the batch year for the first module in this course
                    $studentBatch = $studentSemesters->firstWhere('course_semester_id', $modules->last()->id);
                    // Set batch year to 'N/A' if no studentBatch or if batch is null
                    $batchYear = $studentBatch && $studentBatch->batch ? $studentBatch->batch->academic_year : '';
                @endphp

                <h2 style="color: red">{{ $modules->first()->course->name }} </h2>
                <!-- Group header by Course name and Batch Year -->

                @php
                    // Group modules by batch year
                    $modulesByBatchYear = $modules->groupBy(function ($module) use ($studentSemesters) {
                        $studentBatch = $studentSemesters->firstWhere('course_semester_id', $module->id);
                        return $studentBatch && $studentBatch->batch ? $studentBatch->batch->academic_year : '';
                    });
                @endphp
                @can('access course semester')
                    <a href="{{ route('credits.view', ['courseId' => $modules->first()->course_id, 'semester' => $modules->first()->semester_id, 'batchId' => $studentBatch->batch_id]) }}"
                        class="btn btn-primary">View Credits</a>
                @endcan
                </br></br>

                @foreach ($modulesByBatchYear as $year => $yearModules)
                    <h4>Batch Year: {{ $year }}</h4> <!-- Sub-header for each batch year -->

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Semester</th>
                                <th>Module</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($yearModules as $index => $module)
                                <tr>
                                    <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                    <td>{{ $module->semester->name }}</td>
                                    <td>{{ $module->module->name }}</td>
                                    <td>{{ ucfirst($module->type) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endif
        @endforeach

        @if ($sortedModules->isEmpty())
            <div class="alert alert-warning text-center">No modules found for the selected courses.</div>
        @endif
    </div>
@endsection
